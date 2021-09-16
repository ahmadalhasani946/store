@extends('layouts.home')

@section('title','Contact Us')

@section('banner')
    <div class="page-heading contact-heading header-text">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-content">
                        <h4>contact us</h4>
                        <h2>let’s get in touch</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="find-us">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-heading">
                        <h2>Our Location on Maps</h2>
                    </div>
                </div>
                <div class="col-md-8">
                    <div id="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3008.296608693553!2d28.805223415663164!3d41.06250872403425!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14caa59939b08cf5%3A0x51dbb16a702cb15a!2sMall%20of%20%C4%B0stanbul%20AVM!5e0!3m2!1str!2str!4v1612782504016!5m2!1str!2str" width="100%" height="330px" frameborder="0" style="border:0;" allowfullscreenaria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="left-content">
                        <h4>About our office</h4>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisic elit. Sed voluptate nihil eumester consectetur similiqu consectetur.<br><br>Lorem ipsum dolor sit amet, consectetur adipisic elit. Et, consequuntur, modi mollitia corporis ipsa voluptate corrupti.</p>
                        <ul class="social-icons">
                            <li><a target="_blank" href="https://www.facebook.com/"><i class="fa fa-facebook"></i></a></li>
                            <li><a target="_blank" href="https://www.twitter.com/"><i class="fa fa-twitter"></i></a></li>
                            <li><a target="_blank" href="https://www.linkedin.com/"><i class="fa fa-linkedin"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
