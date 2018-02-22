<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Data;
use Hash;
use Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ActivationCode;
use App\Mail\WelcomeMail;
use App\Mail\NotifyICEContacts;
use App\Mail\ResetClientPassword;
use App\PasswordReset;
use Mailgun;


/**
* @resource Client

* This resource is in charge of dream secure app clients. These are the people that makes use of the application.
* Handles activities like on boarding of a new client or welcoming back of an already existing client.
*/
class ClientController extends Controller
{

    private function crypto_rand_secure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }

    private function getToken($length)
    {
         $token = "";
         $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
         $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
         //$codeAlphabet.= "0123456789";
         $codeAlphabet.= "123456789";
         $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        return $token;
    }

    private function checkEmailAccount(string $email): bool
    {
        //this is to check if the user email already exists it returns
        //true if exists and false otherwise

        $emails = Client::pluck('email')->toArray();
        //dd($emails);
        return (in_array($email, $emails));

    }

    private function generateResponse($status_txt, $code, $body='')
    {
        $return['header']['status'] = $status_txt;
        $return['header']['code'] = $code;

        if($code == "200"){
            $return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
        }

        return $return;
    }//end of generateResponse

    /**
    * Creates new dream secure app user
    * The goal of this function is to create a new user for the dream secure app.
    */
    public function registerClient(Request $request)
    {
        /*$return = $this->generateResponse("DONE", "200", null);
        $return['body']['id'] = $request->all();
        return response()->json($return);*/

        //$user_data = $request->input('user');
        //$user_data = collect($user_data);
        //$test_data = collect(['name' => 'Olakunle', 'age' => 37, 'gender' => 'Male']);
        //dd($test_data['name']);


       /* $validator = Validator::make($request->all(), [
            'last_name' => 'required|min:2|max:100',
            'other_names' => 'required|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'required',
            'gender' => 'required',
            'password' => 'required',

            'ice_1' => 'required',
            'ice_2' => 'required',
            'ice_3' => '',

            'rec_email_1' => 'required',
            'rec_email_2' => 'required',
            'rec_email_3' => ''
            ]);*/

        $this->validate($request, [
            'last_name' => 'required',
            'other_names' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            'password' => 'required',

            'ice_1' => 'required',
            'ice_2' => 'required',
            'ice_3' => '',

            'rec_email_1' => '',
            'rec_email_2' => '',
            'rec_email_3' => ''
        ]);

        //dd($request->email);

        //hash the password
        $request['password'] = Hash::make($request->input('password'));
        //$user_data['password'] = Hash::make($request->input('user.password'));
        //generate the unique code
        $code = $this->getToken(5);

        //add it to $request object
        $request->request->add(['code'=> $code]);
        //$user_data['code'] = $code;

        //dd($request->all());
        //dd($user_data);

        //check the email if it already exists
        $res = $this->checkEmailAccount($request->input('email'));
        //dd($res);
        if( $res == false ){
            //go ahead and create the client
            try{
                $client = Client::create($request->all());
                //$client = Client::create($user_data);
            }catch(Exception $e){
                //Log the messgae if any error
                //$return['header']['status'] = "ERROR";
		//$return['header']['code'] = "110";
                $return = $this->generateResponse("ERROR", "110", null);

                return response()->json($return);
                //return $e;
            }
            //also send a mail to the person

            //return the users id
            //return $client->id;

            $return = $this->generateResponse("DONE", "200", null);
            $return['body']['id'] = $client->id;

            $email_address = $request->email;

            Mail::to($email_address)->send(new ActivationCode($client));

            return response()->json($return);

        }else{
            //return the checkEmailAccount Value / Response
            $data = Client::where('email', $request['email'])->first();
            //$data = Client::where('email', $user_data['email'])->first();
            //return $data->id;

            $return = $this->generateResponse("DONE", "200", null);
            $return['body']['id'] = $data->id;

            return response()->json($return);
        }
    }//end of registerClient

    /**
    * Activate a dream secure app user
    * The goal of this function is to activate any user that is yet to be activated.
    */
    public function activateClient(Request $request)
    {
        //$user_data = $request->input('user');
        $this->validate($request, [
            'code' => 'required'
        ]);

        //select users to see if they exist
        $res = Client::where('code', $request->input('code'))->first();
        //$res = Client::where('code', $user_data['code'])->first();
        if($res ==  null){
            //no user found return null
            $return = $this->generateResponse("ERROR", "200", null);

            return response()->json($return);
        }

        if($res->activated != 1){
            //user is not yet activated
            //update activated and set to 1
            //$res->activated = 1;
            //$res->save();

            try{
                $res->update(['activated' => 1]);

                //send a mail to show all your data and welcome the user on board
                Mail::to($res->email)->send(new WelcomeMail($res));

                //notify the contacts as using them for emergency contact
                if( $res->rec_email_1 != null || $res->rec_email_1 != ""){
                	Mail::to($res->rec_email_1)->send(new NotifyICEContacts($res));
               	}
                if( $res->rec_email_2 != null || $res->rec_email_2 != ""){
                	Mail::to($res->rec_email_2)->send(new NotifyICEContacts($res));
               	}
                if( $res->rec_email_3 != null || $res->rec_email_3 != ""){
                	Mail::to($res->rec_email_3)->send(new NotifyICEContacts($res));
               	}

                $res = Client::where('code', $request->input('code'))->first();

                //dd($res);

                $return = $this->generateResponse("DONE", "200", null);
                $return['body']['userData'] = $res->toArray();

                return response()->json($return);
            }catch(Exception $e){
                \Log::error($e);
                $return = $this->generateResponse("ERROR", "107", null);

                return response()->json($return);
            }
        }else{
            //user is already activated, return success and user data
            $return = $this->generateResponse("DONE", "200", null);
            $return['body']['userData'] = $res->toArray();

            return response()->json($return);
        }


    }//end of activateClient

    public function loginClient(Request $request)
    {
        //$usr_data = $request->input('user');
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        //$exist = null;
        try{
            $exist = Client::where('email', $request->input('email'))->first();
            //$exist = Client::where('email', $user_data['email'])->first();
        }catch(Exception $e){
            \Log::error($e);
        }
        if($exist == null){
            //the user doesn't exist
            $return = $this->generateResponse("ERROR", "101", null);

            return response()->json($return);
        }

        if($exist->activated == 0){
            $return = $this->generateResponse("ERROR", "111", null);

            return response()->json($return);
        }
        //check to see if password matches
        if(Hash::check($request->input('password'), $exist->password)){
            //user exist and gave valid credentials
            $return = $this->generateResponse("DONE", "200", null);
            $return['body']['userData'] = $exist->toArray();

            return response()->json($return);
        }else{
            $return = $this->generateResponse("ERROR", "107", null);

            return response()->json($return);
        }
    }//end of loginClient

    public function getClientDetails(Request $request)
    {
        //$user_data = $request->input('user');
        //dd($request->user);
        /*
        dd($request->user);
        array:10 [
          "id" => "2"
          "last_name" => null
          "other_names" => null
          "email" => null
          "phone" => null
          "ice_1" => null
          "ice_2" => null
          "ice_3" => null
          "rec_email_1" => null
          "rec_email_2" => null
        ]
        */
        $this->validate($request, [
            'id' => 'required'
        ]);
        try{
            $data = Client::find($request->input('id'));
        }catch(Exception $e){
            \Log::error($e);

            $return = $this->generateResponse("ERROR", "106", null);

            return response()->json($return);
        }

        if($data == null){

            $return = $this->generateResponse("ERROR", "110", null);

            return response()->json($return);
        }


        $return = $this->generateResponse('DONE', '200', null);
        $return['body']['userData'] = $data->toArray();

        return response()->json($return);

    }//end of getClientDetails

    public function updateClientDetails(Request $request)
    {

        //$user_data = $request->input('user');

        /*$return = $this->generateResponse("DONE", "200", null);
        $return['body']['id'] = $request->all();
        return response()->json($return);*/

        $this->validate($request, [
            'id' => 'required',
            'last_name' => 'required',
            'other_names' => 'required',
            #'email' => 'required',
            'phone' => 'required',
            #'gender' => 'required',
            #'password' => 'required',

            'ice_1' => 'required',
            'ice_2' => 'required',
            'ice_3' => '',

            'rec_email_1' => '',
            'rec_email_2' => '',
            'rec_email_3' => ''
        ]);

        //$user = $request->input('user');
        //get the client
        try{
            //$client = Client::findOrFail($user_data['id']);
            $client = Client::findOrFail($request->input('id'));
            $client->fill($request->all())->save();
        }catch(Exception $e){
            \Log::error($e);
            $return = $this->generateResponse("ERROR", "107", null);

            return response()->json($return);
        }

        //it was successful
        $client = Client::findOrFail($request->input('id'));
        $return = $this->generateResponse("DONE", "200", null);
        $return['body']['userdata'] = $client->toArray();

        return response()->json($return);

    }//end of updateClientDetails

    public function getClientICEReports(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        try{

            //this is to select all data generated by the person
            $data = Data::where('client_id', $request->input('id'))->latest()->get();

        }catch(Exception $e){
            \Log::error($e);
            $return = $this->generateResponse("ERROR", "107", null);

            return response()->json($return);
        }


        //it was successful
        $return = $this->generateResponse("DONE", "200", null);
        $return['body'] = $data->toArray();

        return response()->json($return);

    }//end of getClientICEReports

    public function testMail()
    {
        //echo 'kunle';
        Mail::send(['text' => 'emails.test_mail'], ['name' => 'Kunle'], function($message){
            $message->to('o.odegbaro@dreammesh.ng', 'To Basketball')->subject('Test Email');
            $message->from('no-reply@olync.net', 'ODEGBARO');
            $message->embedData([], 'sendgrid/x-smtpapi');
        });
        //Mail::to('oodegbaro@gmail.com')->send(new ActivationCode());
        //echo 'end';

        /*Mailgun::send('emails.welcome_test', [], function ($message) {
            $message->to('o.odegbaro@dreammesh.ng', 'Olakunle Testing')->subject('Welcome!');
        });*/
    }

    /**
    *This is to re send the activationCode back to the email address given.
    **/
    public function resendActivationCode(Request $request)
    {
      $this->validate($request, [
        'email' => 'required',
      ]);

      try {
        $client =  Client::where('email', $request->input('email'))->first();
      } catch (Exception $e) {
        \Log::error($e);
        $return = $this->generateResponse("ERROR", "110", null);
        return response()->json($return);
      }

      //send the mail nau
      Mail::to($request->input('email'))->send(new ActivationCode($client));

      //it was successful
      $return = $this->generateResponse("DONE", "200", null);
      $return['body']['id'] = $client->id;

      return response()->json($return);
    }//end of sendUniqueCode

    public function resetPasswordLink(Request $request)
    {
      //this send a link to the email given
      //open a page for them to change to the new password
      //then tell them that the change was sucessful

      /*
      the reset plan is to log a token and email in the db
      then send the link to the owner of the email. if reset is successful
      delete url and token form db.
      If token is more than 2 hours, request or delete
      */

      $this->validate($request, [
        'email' => 'required'
      ]);

      //check email if it exists, create token and save inside the db
      $email = $request->input('email');

      if(!$this->checkEmailAccount($email)){
          $return = $this->generateResponse("ERROR", "110", null);
          return response()->json($return);
      }

      //$token = app('auth.password.broker')->createToken($user);
      $token = str_random(64);

      //save to DB
      //$pwdReset = PasswordReset::create(['email' => $email, 'token' => $token]);
      $pwdReset = new PasswordReset;
      $pwdReset->email = $request->input('email');
      $pwdReset->token = $token;
      //$pwdReset->save();


      try {
        $pwdReset->save();
      } catch (Exception $e) {
        $return = $this->generateResponse("ERROR", "110", null);
        return response()->json($return);
      }

      //get the clients
      $client = Client::where('email', $request->input('email'))->first();

      //generate url
      $url = route('client.reset.link', ['token' => $token]);

      //everything is fine, send mail
      Mail::to($request->input('email'))->send(new ResetClientPassword($client, $url));

      //it was successful
      $return = $this->generateResponse("DONE", "200", null);
      $return['body']['id'] = null;

      return response()->json($return);

    }//end of resetPassword

    public function getResetPage(Request $request, $token)
    {
      //check if token match value in db
      //show a page and reset with the email of that user
      return view('client_reset_pwd', ['token' => $token]);
    }//end of getResetPage

    public function resetClientPassword(Request $request)
    {
      $this->validate($request, [
        'token' => 'required',
        'password' => 'required',
      ]);


      //nau take the mail that rhymes with the token
      //update the person password

      $token = $request->input('token');
      try {

        $data = PasswordReset::where('token', $token)->first();
        $client = Client::where('email', $data->email)->first();
        $client->password = Hash::make($request->input('password'));
        $client->save();

      } catch (Exception $e) {
        \Log::error($e);
        return view('reset_pwd_status', ['status' => 'err']);
      }

      //things went well
      return view('reset_pwd_status', ['status' => 'suc']);
    }//end
}// end of class
