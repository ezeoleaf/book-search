<div class="col-xs-12 text-center">
    <h3>Last Searches</h3>
</div>
<div class="col-xs-12 text-center">
    <div class="well">
    @if (count($lastSearchs) == 0)
        There are not last searches
    @else
        <form method="POST" action="{{ route('search') }}" id="searchLastForm">
            {{ csrf_field() }}
            <input type="hidden" name="search" id="searchLastText" value="">
        </form>
        @foreach ($lastSearchs as $search)
            <a href="#" class="clickSearch" data-search='{{$search->search}}'>{{$search->search}}</a> <br>
        @endforeach
    @endif
    </div>
</div>