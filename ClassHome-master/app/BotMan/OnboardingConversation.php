<?php

namespace App\Botman;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use App\Http\Controllers\Auth\LoginController;

class OnboardingConversation extends Conversation
{
    protected $name;

    protected $email;

    protected $password;

    protected $login_controller;

    public function askName()
    {
        $this->ask('Hi! What is your name?', function(Answer $answer) {
            // Save result
            $this->name = $answer->getText();

            $this->say('Nice to meet you '.$this->name);
            $this->askLogin();
        });
    }
    public function askLogin()
    {
        $this->ask('Do you want to login? (yes/no)', function(Answer $answer) {
            // Save result
             
            if($answer->getText() == 'yes'){
                $this->askEmail();
            }
            else{
                $this->say('Nice to meet you '.$this->name);
            }
            
        });
    }
    public function askEmail()
    {
        $this->ask('One more thing - what is your email address?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, '.$this->name);
            $this->askPassword();
        });
    }

    public function askPassword()
    {
        $this->ask('What is your password?', function(Answer $answer) {
            // Save result
            $this->password = $answer->getText();
            
            $this->say('Login being attempted');
            $this->say($this->tryLogin()); 
            $link = 'localhost:8000/' + $this->email + '/' + $this->password;
            $this->say('Your account is active, click link below to login.');
            //$this->say($link);
            //$count = 0;
            //print("Message here");
            //console.log('message heer')
            
            
        });
    }

    public function tryLogin(){
        
            // Use other controller's method in this controller's method
        return $this->login_controller->authenticate($this->email, $this->password);
        //return true;
    }

    public function run()
    {
        $this->login_controller = new LoginController;
        // This will be called immediately
        $this->askName();
        
    }
}