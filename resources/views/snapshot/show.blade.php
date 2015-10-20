@extends('layouts.default')

@section('main')
    <div class="container-fluid snapshot-header">
        <div class="row">
            <div class="col-xs-2">
                <h2>Permalinker</h2>
            </div>
            <div class="col-xs-9">
                <p class="lead">
                    {{ $snapshot['page_title']['S'] }}
                    <small>
                        <br>
                        Captured on:
                        {{ date('r', strtotime($snapshot['modified_at']['S'])) }}
                    </small>
                </p>
            </div>
            <div class="col-xs-1">
                <a href="/" class="btn btn-primary btn-lg btn-block">Home</a>
            </div>
        </div>
    </div>

    <iframe
        src="http://permalinker-snapshots.s3-website-eu-west-1.amazonaws.com/{{ $id }}/index.html"
        height="100%"
        width="100%"
        class="snapshot-iframe"
        seamless
        frameBorder=0
    >
        <p>Sorry, your browser does not support iframes.</p>
    </iframe>
@stop
