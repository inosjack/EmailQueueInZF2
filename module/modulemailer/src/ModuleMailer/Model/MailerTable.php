<?php
/**
 * ModuleMailer
*/

namespace ModuleMailer\Model;

use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\TableGateway\TableGateway;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailerTable extends AbstractTableGateway implements ServiceLocatorAwareInterface
{
    protected $config;
    protected $service_manager;
    protected $tableGateway;

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->service_manager = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->service_manager;
    }

    public function getTableGateway()
    {
        return $this->tableGateway;
    }
    
    public function __construct(TableGateway $tableGateway, $config)
    {
        $this->tableGateway = $tableGateway;
        $this->config = $config;
    }

    /**
     * @param string $queue_name
     * @param \Zend\Mail\Message $mail
     */
    public function add($queue_name,\Zend\Mail\Message $mail) {
        $this->tableGateway->insert(
                array(
                    'queue_name'=>$queue_name,
                    'mail'=>  serialize($mail)
                )
        );
    }

    /**
     * @param \ModuleMailer\Model\Mailer $mail
     */
    public function markAsSent(\ModuleMailer\Model\Mailer $mail)
    {
        $this->tableGateway->update(array("status" => "sent"), array('id' => $mail->id));
    }

    /**
     * @param \ModuleMailer\Model\Mailer $mail
     */
    public function markAsError(\ModuleMailer\Model\Mailer $mail)
    {
        $this->tableGateway->update(array("status" => "error"), array('id' => $mail->id));
    }

    /**
     * @param \ModuleMailer\Model\Mailer $mail
     */
    public function markAsWaiting(\ModuleMailer\Model\Mailer $mail)
    {
        $this->tableGateway->update(array("status" => "waiting"), array('id' => $mail->id));
    }


    /**
     * @param string $queue_name
     * @throws \Exception
     * @return \ModuleMailer\Model\Mailer|boolean return \ModuleMailer\Model\Mailer or false if queue is empty
     */
    public function getWaitingFromQueue($queue_name) {
        try {
            $connection = $this->tableGateway->adapter->getDriver()->getConnection();
            $connection->execute('SET AUTOCOMMIT = 0');
            $connection->beginTransaction();

            $sql = new Sql($this->tableGateway->adapter);
            $select = $sql->select();
            $select->from($this->config['table']);
            $select->where(array('status'=>'waiting','queue_name'=>$queue_name));
            $select->order('created');
            $resultset = $connection->execute($select->getSqlString($this->tableGateway->adapter->getPlatform()));
            $mailer = $resultset->current(); 
            
            //Queue is empty
            if (!$mailer) {
                $connection->commit();
                $connection->execute('SET AUTOCOMMIT = 1');
                return false;
            }
            
            $sql = new Sql($this->tableGateway->adapter);
            $update = $sql->update($this->config['table']);
            $update->set(array('status'=>'processing'));
            $update->where(array('id'=>$mailer['id'],'status'=>'waiting'));
            $connection->execute($update->getSqlString($this->tableGateway->adapter->getPlatform()));
            $connection->commit();
            $connection->execute('SET AUTOCOMMIT = 1');                        
            $mailerObj = new Mailer();
            $mailerObj->exchangeArray($mailer);
            $mailerObj->status ='processing';
            return $mailerObj;
        } catch (\Exception $e) {
            $connection = $this->tableGateway->adapter->getDriver()->getConnection();
            $connection->rollback();
            $connection->execute('SET AUTOCOMMIT = 1');
            throw new \Exception($e->getMessage());
        }       
    }
}