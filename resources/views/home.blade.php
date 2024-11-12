@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="row">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('VIEW-PROCESS-DATA') }}'">Go To Process Data</button>
                </div>
            </div>
            <div class="row mt-4">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                        <th scope="col">Client Id</th>
                        <th scope="col">Number Of Payment</th>
                        <th scope="col">First Payment Date</th>
                        <th scope="col">Last Payment Date</th>
                        <th scope="col">Loan Amount</th>
                        </tr>
                    </thead>
                    <tbody class="table-light">
                        @if(isset($LoanDetails))
                            @foreach($LoanDetails as $LoanDetail)
                            <tr>
                                <td style="text-align:center">{{ $LoanDetail->client_id }}</td>
                                <td style="text-align:center">{{ $LoanDetail->num_of_payment }}</td>
                                <td style="text-align:center">{{ $LoanDetail->first_payment_date }}</td>
                                <td style="text-align:center">{{ $LoanDetail->last_payment_date }}</td>
                                <td style="text-align:right">{{ $LoanDetail->loan_amount }}</td>
                            </tr> 
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
@endsection
