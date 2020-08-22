<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>TeamView Concept Tester</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
  </head>
  <body>

      <div class="container-fluid">
          <nav class="navbar navbar-expand-lg sticky-top navbar-light bg-light">

              <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <a class="navbar-brand" href="#">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                  @if ( auth()->user()->memberships->count())
                      @if (auth()->user()->memberships->first()->group->name == 'Coverfly')
                      <span class="badge badge-primary">{{ auth()->user()->memberships->first()->group->name }}</span>
                      @endif
                      @if (auth()->user()->memberships->first()->group->name == 'CAA')
                      <span class="badge badge-success">{{ auth()->user()->memberships->first()->group->name }}</span>
                      @endif
                  @else
                      <span class="badge badge-warning">No Group</span>
                  @endif
              </a>

              <div class="collapse navbar-collapse" id="navbarTogglerDemo03">

                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                      <li class="nav-item active">
                        @impersonating
                        <a class="nav-link" href="{{ route('impersonate.leave') }}">Unimpersonate </a>
                        @endImpersonating
                      </li>
                    </ul>


                <div class="dropdown my-2 my-lg-0 mr-4">
                    @if ( !app('impersonate')->isImpersonating() )
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Impersonate
                  </button>
                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">

                      <h6 class="dropdown-header">Coverfly</h6>
                      @foreach ($users->sortBy('first_name') AS $user)
                        @if ( Str::contains ($user->email, 'coverfly') && $user->id != auth()->user()->id )
                          <a class="dropdown-item" href="{{ route('impersonate', $user->id) }}">{{ $user->first_name }} {{ $user->last_name }}
                              @if ($user->memberships->first() && $user->memberships->first()->role == 'owner')
                              <i class="fa fa-check-circle text-success pull-right"></i>
                              @endif
                          </a>
                        @endif
                      @endforeach
                      <div class="dropdown-divider"></div>

                      <h6 class="dropdown-header">CAA</h6>
                      @foreach ($users->sortBy('first_name') AS $user)
                        @if ( Str::contains ($user->email, 'caa') && $user->id != auth()->user()->id )
                          <a class="dropdown-item" href="{{ route('impersonate', $user->id) }}">{{ $user->first_name }} {{ $user->last_name }}
                              @if ($user->memberships->first() && $user->memberships->first()->role == 'owner')
                              <i class="fa fa-check-circle text-success pull-right"></i>
                              @endif
                          </a>
                        @endif
                      @endforeach
                      <div class="dropdown-divider"></div>

                      <h6 class="dropdown-header">(None)</h6>
                      @foreach ($users->sortBy('first_name') AS $user)
                        @if ( !Str::contains ($user->email, 'coverfly') && !Str::contains ($user->email, 'caa') && $user->id != auth()->user()->id )
                          <a class="dropdown-item" href="{{ route('impersonate', $user->id) }}">{{ $user->first_name }} {{ $user->last_name }}</a>
                        @endif
                      @endforeach


                  </div>


                  @endif
                </div>


              </div>


          </nav>

          @if ( session('error') )
              <div class="row mt-3">
                  <div class="col-md-12">
                      <div class="alert alert-danger" role="alert">
                     {{ session('error') }}
                     </div>
                  </div>
              </div>
          @endif

          @if ( count($errors) )
              <div class="row mt-3">
                  <div class="col-md-12">
                      <div class="alert alert-danger" role="alert">
                     {{ $errors->first() }}
                     </div>
                  </div>
              </div>
          @endif

          @if ( session('success') )
              <div class="row mt-3">
                  <div class="col-md-12">
                      <div class="alert alert-success" role="alert">
                     {{ session('success') }}
                     </div>
                  </div>
              </div>
          @endif

          <div class="row mt-4">

              <div class="col-md-4">
                  <h2 class="">Your Groups</h2>
                      @if ( auth()->user()->memberships->count() )
                          <ul class="list-group">
                             @foreach (auth()->user()->memberships AS $membership)
                            <li class="list-group-item">
                                <a href="#" class="list-group-item list-group-item-action">
                                  <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $membership->group->name }}</h5>
                                    <small><span class="badge badge-pill badge-{{ $membership->role == 'owner' ? 'success' : 'light' }}">{{ $membership->role }}</span></small>
                                  </div>
                                  <p class="mb-1">{{ $membership->group->description }}</p>
                                  <p>
                                      @foreach ($membership->group->memberships AS $mem)
                                          <span class="badge badge-pill badge-{{ $mem->role == 'owner' ? 'success' : 'light' }}">{{ $mem->member->first_name }} {{ $mem->member->last_name }}</span>
                                      @endforeach
                                  </p>
                                  <small>Added by {{ $membership->creator->first_name }} {{ $membership->creator->last_name }} {{ $membership->created_at->diffForHumans() }}</small>
                                </a>
                                </li>
                            @endforeach
                          </ul>
                      @else
                         <div class="alert alert-warning" role="alert">
                         You are not a member of any groups.
                        </div>
                      @endif


                      <h2 class="mt-4">All Your Comments</h2>
                          @if (auth()->user()->comments->count())
                          <ul class="list-group list-group-flush">
                              @foreach (auth()->user()->comments AS $comment)
                                  <li class="list-group-item">
                                      {{ Crypt::decrypt($comment->comment) }}<br />
                                      <small class="text-muted">{{ $comment->created_at->diffForHumans() }} on {{ $comment->commentable->name }}</small>
                                  </li>
                              @endforeach
                          </ul>
                          @else
                              <div class="alert alert-warning" role="alert">
                              You have not made any comments.
                             </div>
                          @endif

              </div> <!-- end col -->


              <div class="col-md-4">
                  <h2 class="">Project + Comments</h2>

                      <div class="card">
                        <div class="card-body">
                          <h5 class="card-title">{{ $project->name }}</h5>
                          <h6 class="card-subtitle mb-2 text-muted">Added by {{ $project->creator->first_name }} {{ $project->creator->last_name }} {{ $project->created_at->diffForHumans() }}</h6>
                          <p class="card-text">{{ $project->info }}</p>
                          <form method="POST" action="{{ route ('comment', $project->id) }}">
                              @csrf
                              <div class="form-group">
                                <label for="comment">Add A Comment</label>
                                <textarea name="comment" class="form-control" id="comment" rows="3">{{ old('comment') }}</textarea>
                              </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </form>

                        </div>
                      </div>

                      <div class="alert alert-info mt-3" role="alert">
                      @if (auth()->user()->memberships->count())
                          These comments are only visible to members of your groups.
                      @else
                          Your own comments are only visible to you.
                      @endif
                     </div>

                      @if ($project->comments->count())
                      <ul class="list-group list-group-flush">
                          @foreach ($project->comments AS $comment)
                              <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-md-12">
                                          {{ Crypt::decrypt($comment->comment) }}<br />
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <small class="text-muted">
                                              {{ $comment->commented->first_name }} {{ $comment->commented->last_name }}
                                              @if ($comment->commented->memberships->count () > 0)
                                                  @foreach ($comment->commented->memberships AS $membership)
                                                      &nbsp;<span class="badge badge-pill badge-{{ $membership->role == 'owner' ? 'success' : 'light' }}">{{ $membership->group->name }}</span>
                                                  @endforeach
                                              @endif

                                              @if ( auth()->user()->memberships->count() && !auth()->user()->memberships->first()->group->memberships->contains ('member_id', $comment->commented->id) )
                                                  <br /><small class="text-muted"><em><i class="text-warning fa fa-warning"></i> {{ $comment->commented->first_name }} is not currently a member of {{ auth()->user()->memberships->first()->group->name }}.</em></small>
                                              @endif

                                          </small>
                                      </div>
                                      <div class="col-md-6 text-right">
                                          <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                      </div>
                                  </div>

                              </li>
                          @endforeach
                      </ul>
                      @else
                          <div class="alert alert-warning" role="alert">
                          This project does not have any comments.
                         </div>
                      @endif


              </div> <!-- end col -->

              <div class="col-md-2">
                  <h2 class="text-center">Add</h2>
                  @if (auth()->user()->memberships->count())
                      @if ( auth()->user()->can ('increment', auth()->user()->memberships->first()->group))
                          <ul class="list-group list-group-flush">
                              @foreach ($users->sortBy('first_name') AS $user)
                                  @if ( !auth()->user()->memberships->first()->group->memberships->contains ('member_id', $user->id) )
                                  <li class="list-group-item">
                                      {{ $user->first_name }}  {{ $user->last_name }} <a class="btn btn-sm btn-success pull-right" href="{{ route ('group', [auth()->user()->memberships->first()->group->id, 'add', $user->id]) }}"><i class="fa fa-plus-circle"></i></a>
                                  </li>
                                @endif
                              @endforeach
                          </ul>
                      @else
                          <div class="alert alert-warning mt-3" role="alert">
                          You cannot add members to your group.
                         </div>
                      @endif
                  @else
                      <div class="alert alert-warning mt-3" role="alert">
                      You are not in any groups.
                     </div>
                  @endif
              </div>

              <div class="col-md-2">
                  <h2 class="text-center">Remove</h2>
                  @if (auth()->user()->memberships->count())
                      @if ( auth()->user()->can ('decrement', auth()->user()->memberships->first()->group))
                          <ul class="list-group list-group-flush">
                              @foreach (auth()->user()->memberships->first()->group->memberships->sortBy('first_name') AS $membership)
                                  @if ( auth()->user()->id != $membership->member->id && $membership->role != 'owner' )
                                  <li class="list-group-item">
                                      {{ $membership->member->first_name }}  {{ $membership->member->last_name }} <a class="btn btn-sm btn-danger pull-right" href="{{ route ('group', [auth()->user()->memberships->first()->group->id, 'remove', $membership->member->id]) }}"><i class="fa fa-times-circle"></i></a>
                                  </li>
                                @endif
                              @endforeach
                          </ul>
                      @else
                          <div class="alert alert-warning mt-3" role="alert">
                          You cannot remove members from your group.
                         </div>
                      @endif
                  @else
                      <div class="alert alert-warning mt-3" role="alert">
                      You are not in any groups.
                     </div>
                  @endif
              </div>
          </div> <!-- end row -->




      </div> <!-- end container fluid -->

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
