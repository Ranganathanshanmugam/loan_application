@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="row">
                <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('PROCESS-DATA') }}'">Process Data</button>
                </div>
            </div> 
            
            <div class="row mt-4">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-primary">
                            @if(isset($psColumnNames))
                            <tr> 
                                @foreach($psColumnNames as $psColumnName)
                                    <th scope="col">{{ $psColumnName}}</th>
                                @endforeach
                            </tr>
                            @endif
                        </thead>
                        <tbody class="table-light">
                            @if(isset($psEmiDetails))
                                @foreach($psEmiDetails as $psEmiDetail)
                                <tr> 
                                    @foreach($psColumnNames as $psColumnName)
                                        <td scope="col">{{ $psEmiDetail->$psColumnName}}</td>
                                    @endforeach 
                                </tr> 
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
