<x-layout>
    <div class="container py-md-5 container--narrow">
      @unless($feedPosts->isEmpty()) 
      <h2 class="text-center mb-4">The Latest From People You Follow</h2>
      <div class="list-group">
        @foreach ($feedPosts as $feedPost)
        <a href="/post/{{$feedPost->id}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$feedPost->user->avatar}}" />
          <strong>{{$feedPost->title}}</strong> <span class="text-muted small">by {{$feedPost->user->username}}</span> on {{$feedPost->created_at->format('j/n/Y')}}
        </a>
        @endforeach
      </div>
          <div class="mt-4">
            {{ $feedPosts->links() }}
          </div>
      @else
      <div class="text-center">
        <h2>Hello <strong>{{auth()->user()->username}}</strong>, your feed is empty.</h2>
        <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you don&rsquo;t have any friends to follow that&rsquo;s okay; you can use the &ldquo;Search&rdquo; feature in the top menu bar to find content written by people with similar interests and then follow them.</p>
      </div>
    </div>
      @endunless
        
</x-layout>