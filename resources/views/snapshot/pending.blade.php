@extends('layouts.default')

@section('main')
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>Pending...</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p>We're not ready yet - but don't worry! This page will automatically refresh itself when we are!</p>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        function reloadOnReady() {
            $.get("/status/{{ $snapshot['id'] }}", function(data) {
                if(data === 'ready')
                {
                    location.reload(true);
                }
            });
        }

        setInterval(reloadOnReady, 2000);
    </script>

