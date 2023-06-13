<x-profile>
    <div class="list-group">
        @foreach ($followers as $follower)
          <a href="/profile/{{$follower->user->username}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$follower->user->avatar}}" />
            <strong>{{$follower->user->username}}</strong> on {{$follower->created_at->format('j/n/Y')}}
          </a>
        @endforeach
      </div>
</x-profile>