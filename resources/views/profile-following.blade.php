<x-profile>
    <div class="list-group">
        @foreach ($following as $follow)
          <a href="/profile/{{$follow->userBeingFollowed->username}}" class="list-group-item list-group-item-action">
            <img class="avatar-tiny" src="{{$follow->userBeingFollowed->avatar}}" />
            <strong>{{$follow->userBeingFollowed->username}}</strong> on {{$follow->created_at->format('j/n/Y')}}
          </a>
        @endforeach
      </div>
</x-profile>