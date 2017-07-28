<?php
/**
 * ModuleMailer
 */

namespace ModuleMailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class ConsoleController extends AbstractActionController 
{

    public function processAction()
    {
        $queue = $this->getRequest()->getParam('queue');
        file_put_contents('php://stdout', 'Processing queue '.$queue.".\n",FILE_APPEND);

        /** @var \ModuleMailer\Service $mailer */

        $mailer = $this->getServiceLocator()->get('ModuleMailer');
        $mailer->processQueue($queue);
    }
}