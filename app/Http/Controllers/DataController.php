<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use Twilio;
use App\Data;
use App\Mail\AlertICEContacts;
use App\Client;
use Yajra\Datatables\Datatables;



class DataController extends Controller
{
    private function generateResponse(string $status_txt, string $code, $body='') : array
    {
        $return['header']['status'] = $status_txt;
        $return['header']['code'] = $code;

        if($code == "200"){
            $return['header']['completedTime'] = date('l jS \of F Y h:i:s A');
        }

        return $return;
    }//end of generateResponse
    //
    public function create(Request $request)
    {
    	/*$return = $this->generateResponse("DONE", "200", null);
        $return['body']['id'] = $request->all();
        return response()->json($return);*/
        $this->validate($request, [
           'message' => 'required',
           'lon' => 'required',
           'lat' => 'required',
           'id'=>'required'
        ]);
        $data = $request->all();
        //dd($data);
        $data['client_id'] = $data['id'];
        unset($data['id']);
        //dd($data);
        //add the data to the table and send a mail too
        try{
            $save_dt = Data::create($data);
        }catch(Exception $e){
            \Log::error($e);
            //post operation failed
            $return = $this->generateResponse("ERROR", "110", null);

            return response()->json($return);
        }

        //everything went well, send a a mail nau to notify the ICE email contacts.
        //get client data
        $client_data = Client::find($data['client_id']);
        if($client_data->rec_email_1 != null || $client_data->rec_email_1 != ""){
        	Mail::to($client_data->rec_email_1)->send(new AlertICEContacts($client_data, $save_dt));
        }
        if($client_data->rec_email_2 != null || $client_data->rec_email_2 != ""){
        	Mail::to($client_data->rec_email_2)->send(new AlertICEContacts($client_data, $save_dt));
        }
        //Mail::to($client_data->rec_email_2)->send(new AlertICEContacts($client_data, $save_dt));
        if($client_data->rec_email_3 != null || $client_data->rec_email_3 != ""){
        	Mail::to($client_data->rec_email_3)->send(new AlertICEContacts($client_data, $save_dt));
        }


        //return success
        $return =  $this->generateResponse("DONE", "200", null);
        return response()->json($return);

    }//end of create()

    //this takes us to the view
    public function viewAllReports(Request $request)
    {
        return view('reports');
    }

    public function allReportsData(Request $request)
    {
        $alerts =  Data::all();
        return Datatables::of($alerts)
            ->addColumn('full_name', function($alert){
                return $alert->client->last_name . ' ' . $alert->client->other_names;
            })
            ->editColumn('created_at', function($alert){
                return date('M jS, Y', strtotime($alert->created_at));
            })
            ->make(true);
        //return Datatables::of(Data::query)->make(true);
    }//end of allData

    public function testSMS(Request $request)
    {
      $phone = '+2349097694139';
      $message = 'This is me testing twilio API.';
      Twilio::message($phone, $message);
      return 'sent';
    }//end of testSMS
}
