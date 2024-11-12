<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    //==========================================================//
    // Function : index()
    //==========================================================//
    public function index()
    {

        $psLoanDetails = DB::table('loan_details')->get(); 

        return view('home')
               ->with('LoanDetails',  $psLoanDetails);
    }

    //==========================================================//
    // Function : ProcessData()
    //==========================================================//
    public function ProcessData()
    {
        $psColumnNames = array();
        $psEmiDetails  = array();

        return  view('ProcessData')
                ->with('psColumnNames', $psColumnNames)
                ->with('psEmiDetails', $psEmiDetails);
    }

    //==========================================================//
    // Function : GetProcessData()
    //==========================================================//
    public function GetProcessData(){
        DB::statement('DROP TABLE IF EXISTS emi_details');
        
        $psMinDate = DB::table('loan_details')->min('first_payment_date');
        $psMaxDate = DB::table('loan_details')->max('last_payment_date');

        $psStartMonth   = Carbon::parse($psMinDate)->startOfMonth();
        $psEndMonth     = Carbon::parse($psMaxDate)->startOfMonth();
        
        $psColumnNames = [];
        
        # Format: YYYY_MMM 
        while ($psStartMonth <= $psEndMonth) {
            
            $psColumnNames[] = $psStartMonth->format('Y_M'); 

            $psStartMonth->addMonth();
        }
        
        $psColumnsValue = implode(', ', array_map(fn($col) => "`$col` DECIMAL(16, 2) DEFAULT 0.00", $psColumnNames));
        
        DB::statement("
                        CREATE TABLE emi_details (
                            client_id INT PRIMARY KEY,
                                $psColumnsValue
                            )");
            
        $psLoanDetails = DB::table('loan_details')->get();
        
        foreach ($psLoanDetails as $psLoanDetail) {
            
            $piClientId       = $psLoanDetail->client_id;
            $piEmiCount       = $psLoanDetail->num_of_payment;
            
            $psLoanStartDate = Carbon::parse($psLoanDetail->first_payment_date)->startOfMonth();
            $psLoanEndDate   = Carbon::parse($psLoanDetail->last_payment_date)->startOfMonth();
            
            $piLoanAmount       = $psLoanDetail->loan_amount;
            
            #Calculate EMI Amount 
            $piEmiAmount      = round($piLoanAmount / $piEmiCount, 2);
            $totalEmiPaid     = $piEmiAmount * ($piEmiCount - 1);
            $piLastEmiAmount  = round($piLoanAmount - $totalEmiPaid, 2);
            
            $psEmiData = ['client_id' => $piClientId];
            
            $psCurrentDate = $psLoanStartDate;
            
            for ($i = 1; $i <= $piEmiCount; $i++) {
                
                $psMonthColumn = $psCurrentDate->format('Y_M');
                
                $psEmiData[$psMonthColumn] = ($i === $piEmiCount) ? $piLastEmiAmount : $piEmiAmount;
                
                $psCurrentDate->addMonth();
            }
            
            #Insert or update the Emi Details 
            DB::table('emi_details')
            ->updateOrInsert([
                'client_id' => $piClientId
            ], $psEmiData);
        }
        
        $psEmiDetails = DB::table('emi_details')->get();

        $psColumnNames = Schema::getColumnListing('emi_details');  
       
        return view('ProcessData')
                ->with('psColumnNames', $psColumnNames)
                ->with('psEmiDetails', $psEmiDetails);
    }

}
