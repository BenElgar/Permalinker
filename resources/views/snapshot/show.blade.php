@extends('layouts.default')

@section('title')
    {{ $snapshot['page_title'] }}
@stop

@section('main')
    <div class="container-fluid snapshot-header">
        <div class="row">
            <div class="col-xs-2">
                <h2>
                    <a href="/">Permalinker</a>
                </h2>
            </div>
            <div class="col-xs-8">
                <p class="lead">
                    <a href="{{ $snapshot['url'] }}">
                        {{ $snapshot['page_title'] }}
                        <sup>
                            <i class="fa fa-external-link"></i>
                        </sup>
                    </a>
                    <small>
                        <br>
                        Captured on:
                        {{ date('r', strtotime($snapshot['modified_at'])) }}
                    </small>
                </p>
            </div>
            <div class="col-xs-2">
                <a href="/" class="btn btn-primary btn-lg btn-block">Create a Permalink</a>
            </div>
        </div>
    </div>

    <iframe
        src="http://permalinker-snapshots.s3-website-eu-west-1.amazonaws.com/{{ $snapshot['id'] }}/index.html"
        height="100%"
        width="100%"
        class="snapshot-iframe"
        seamless
        frameBorder=0
    >
        <p>Sorry, your browser does not support iframes.</p>
    </iframe>
@stop
