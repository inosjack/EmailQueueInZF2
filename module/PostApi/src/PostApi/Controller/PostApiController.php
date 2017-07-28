<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 7/26/2017
 * Time: 11:57 PM
 */
namespace PostApi\Controller;

use Zend\Config\Processor\Queue;
use Zend\Form\Annotation\ValidationGroup;
use Zend\Validator\EmailAddress;
use Zend\View\Model\JsonModel;
use Zend\Mail;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;


class PostApiController extends \Zend\Mvc\Controller\AbstractRestfulController
{
    /**
     * @param mixed $data
     * @return JsonModel
     */
    public function create($data)
    {
        $email = $data['emailId'];
        $name = $data['name'];

        //Validate email Id
        $validator = new EmailAddress();

        if ($validator->isValid($email)) {
            //Email valid
            return $this->sendMail($email,$name);
        } else {
            $messages = array();
            // email is invalid; print the reasons
            foreach ($validator->getMessages() as $messageId => $message) {
                $messages[] =  "Validation failure : $message";
            }
            return new JsonModel(array('status' => 'fail', 'messages' => $messages));
        }
    }

    /**
     * @param $email
     * @param $name
     * @return JsonModel
     */
    public function sendMail($email, $name)
    {
        $mail = new Message();
        $mail->addTo($email)
            ->addFrom('inos.jay55@yahoo.com')
            ->setSubject('Testing')
            ->setBody("This is a default testing mail");

        $this->getServiceLocator()->get('ModuleMailer')->add('EmailQueue',$mail);

        return new JsonModel(array(
            'status' => 'success',
            'message' => 'Mail successfully send to ' . $name
        ));
    }
}
