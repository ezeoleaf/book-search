@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-3">
            @include('shared.sidebar')
        </div>
        <div class="col-xs-9">
            <div class="row">
                <div class="col-xs-12">
                    <form action="{{ route('search') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="input-group">
                            <input type="text" name="search" id="search" value="{{ isset($search) ? $search : '' }}" class="form-control" placeholder="Search for..." required>
                            <input type="hidden" name="first" value="first"/>
                            <div class="input-group-btn">
                                <button class="btn btn-default inlineButtonSearch" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                <a href="{{ route('home') }}"><button class="btn btn-default inlineButtonSearch" type="button">Reset</button></a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="books">
                <div class="row">
                    @if (isset($books))
                        @if ($books->totalItems > 0)
                        <div class="col-xs-12">
                                @foreach (array_chunk($books->items, 4) as $indexChunk => $bookChunk)
                                    <div class="row display-flex">
                                        @foreach ($bookChunk as $index => $book)
                                            <div class="col-xs-6 col-md-3 colBook thumDiv" data-id="{{$indexChunk}}-{{ $index }}">
                                                <div class="thumbnail thumbDescription" id="thumb-desc-{{$indexChunk}}-{{$index}}">
                                                    <div class="caption">
                                                        <p>{{ isset($book->volumeInfo->description) ? $book->volumeInfo->description : 'There is no description available for this book' }}</p>
                                                    </div>
                                                </div>
                                                <div class="thumbnail thumbPrimary" id="thumb-prim-{{$indexChunk}}-{{$index}}">
                                                    <img src="{{ isset($book->volumeInfo->imageLinks->smallThumbnail) ? $book->volumeInfo->imageLinks->smallThumbnail : asset('img/nocover.png') }}" {{ !isset($book->volumeInfo->imageLinks->smallThumbnail) ? 'width=128' : ''}} alt="{{ $book->volumeInfo->title }}">
                                                    <div class="caption">
                                                        <h4>{{ $book->volumeInfo->title }}</h4>
                                                        <p>{{ isset($book->volumeInfo->authors) ? implode(', ',$book->volumeInfo->authors) : '' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                                <div class="row">
                                    <div class="col-xs-5 text-right">
                                        @if(isset($offset))
                                            <form method="POST" class="form-inline" action="{{ route('search') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="search" value="{{ $search }}">
                                                <input type="hidden" name="offset" value="{{ $offset - env('GOOGLE_PAGINATION') }}">
                                                <input type="submit" id="prevUrl" value="<" class="btn btn-default" {{($offset < env('GOOGLE_PAGINATION')) ? 'disabled' : '' }} />
                                            </form>
                                    </div>
                                    <div class="col-xs-2 text-center" id="paginationValue">
                                        <strong>{{ ($offset + env('GOOGLE_PAGINATION') > $books->totalItems) ? $books->totalItems : $offset + env('GOOGLE_PAGINATION') }} of {{ $books->totalItems }}</strong> 
                                    </div>
                                    <div class="col-xs-5 text-left">
                                            <form method="POST" class="form-inline" action="{{ route('search') }}">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="search" value="{{ $search }}">
                                                <input type="hidden" name="offset" value="{{ ($offset + env('GOOGLE_PAGINATION')) }}">
                                                <input type="submit" id="prevUrl" value=">" class="btn btn-default" {{(($offset + env('GOOGLE_PAGINATION')) > $books->totalItems) ? 'disabled' : '' }} />
                                            </form>
                                        @endif
                                    </div>
                                </div>
                        </div>
                        @else
                        <div class="col-xs-12 text-center">
                            <div class="well">There are not books that match this search</div>
                        </div>
                        @endif
                    @else
                        <div class="col-xs-12 text-center">
                            <div class="well">Try searching an author or a title</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/home.js') }}"></script>
@endsection