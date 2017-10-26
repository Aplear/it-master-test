<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

class InitController extends Controller
{
    /**
     * @var $mailbox
     */

    protected $mailbox;

    /**
     * @var $imapPath
     */
    private $imapPath = '{imap.gmail.com:993/imap/ssl}INBOX';

    /**
     * @var $imapLogin
     */
    private $imapLogin = 'testovich.it.master@gmail.com';

    /**
     * @var $imapPssword
     */
    private $imapPassword = '384yhDwhefih';

    /**
     * Initial method
     */
    public function init()
    {
        parent::init();
        //argument is the directory into which attachments are to be saved:
        $this->mailbox = new \PhpImap\Mailbox($this->imapPath, $this->imapLogin,$this->imapPassword, __DIR__);

    }
}