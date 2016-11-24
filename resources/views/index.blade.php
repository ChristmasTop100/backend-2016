@extends('layouts.default')

@section('header')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Christmastop <span class="green">100</span></h1>

                @if (!Auth::check())
                    <p class="text-center">
                        <a data-toggle="modal" data-target="#login-modal">Login</a> with your e-mail adres to vote!
                    </p>
                @endif
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container songs">
        @foreach ($songs as $song)
            <div class="row" data-id="{{ $song->id }}">
                <div class="col-sm-1">
                    <div class="cover" style="background-image: url({{ $song->image }})">
                        <div class="overlay"></div>
                    </div>
                </div>

                <div class="col-sm-1 text-center">
                    <a target="_blank" class="play-wrapper" href="{{ $song->url }}">
                        <span class="play"></span>
                    </a>
                </div>

                <div class="col-sm-8 name">
                    {{ $song->name }} - <span class="artist">{{ $song->artist }}</span>
                </div>

                <div class="col-sm-2 vote text-right">
                    @if (Auth::check())
                        <span class="counter">
							{{ isset($song->vote[0]) ?  $song->vote[0]->score : 0 }}
						</span>
                        <a href="#"><i class="ion-android-add vote-plus"></i></a>
                        <a href="#"><i class="ion-android-remove vote-minus"></i></a>
                    @endif
                </div>
            </div>
        @endforeach

    </div>

    @if (Auth::check())
        <div class="votes-left">
            <span class="total-user-counter">100</span>
        </div>
    @endif
@stop

@section('footer')
    <!-- Modal -->
    <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="login-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Login</h4>
                </div>
                <div class="modal-body">
                    <div class="message">
                    </div>
                    <form action="">
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Email">
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <input type="hidden" id="_token" value="{{ csrf_token() }}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="login">Login</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="done-voting-modal" tabindex="-1" role="dialog" aria-labelledby="done-voting-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Done!</h4>
                </div>
                <div class="modal-body">
                    <div class="message">
                        Thanks for voting!

                        <img src="https://i.ytimg.com/vi/WCAQOX6jlFk/hqdefault.jpg">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        (function ($) {
            $('button#login').click(function () {
                $.ajax({
                    type: "POST",
                    url: "auth/login",
                    dataType: "json",
                    data: {
                        _token: $('form #_token').val(),
                        email : $('form #email').val(),
                        password : $('form #password').val()
                    },
                    success: function (msg) {
                        if (msg == 'Authenticated') {
                            location.reload();
                        }
                        else {
                            $(".modal-body .message").html(msg)
                        }
                    },
                    error: function (msg) {
                        console.log(msg);
                    }
                });
            });


            var element, totalVotes = 100, bigCounter = $('.total-user-counter');

            $('.songs').find('.row').each(function (i, el) {
                element = $(el);

                function castVote(score, id) {
                    $.ajax({
                        type: "POST",
                        url: "song/vote",
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            score: score,
                            id: id
                        },
                        error: function () {
                            alert('Unable to cast votes, try again later.');
                        }
                    });
                }
                $('span.counter', element).each(function(i, e) {
                    totalVotes -= parseInt($(e).html());
                    bigCounter.html(totalVotes);

                    if (totalVotes == 0) {
                        $('#done-voting-modal').modal();
                    }
                });

                $('.vote-plus', element).on('click', function (e) {
                    e.preventDefault();
                    var parentElement = $(e.target).parent().parent();
                    var counter = $('.counter', parentElement);
                    var count = counter.html();

                    if (totalVotes > 0) {
                        count++;
                        totalVotes--;
                        bigCounter.html(totalVotes);
                        counter.html(count);
                        castVote(count, parentElement.parent().data('id'));
                    }
                });

                $('.vote-minus', element).on('click', function (e) {
                    e.preventDefault();
                    var parentElement = $(e.target).parent().parent();
                    var counter = $('.counter', parentElement);
                    var count = counter.html();

                    if (count > 0 && totalVotes < 100) {
                        count--;
                        totalVotes++;
                        bigCounter.html(totalVotes);

                        counter.html(count);
                        castVote(count, parentElement.parent().data('id'));
                    }
                });
            });

        })(jQuery);
    </script>
@stop
