<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @stack('head') <!--this helps to create a stack-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <!-- Bootstrap Icons -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    @vite('resources/js/app.js')



    @if (Auth::user())
        <script>
            const markAllReadRoute = "{{ route('notifications.markAllRead') }}";
            const csrfToken = "{{ csrf_token() }}";
            const readNotificationRoute =
                "{{ route('notifications.read', ['notificationId' => 'PLACEHOLDER']) }}"; // Placeholder for notification ID
            const userId = "{{ Auth::user()->id }}";

            document.addEventListener("DOMContentLoaded", function() {
                if (typeof Echo !== 'undefined') {
                    Echo.channel('notification')
                        .listen('BroadcastNotificationEvent', (event) => {
                            fetchNotifications();
                        });
                }
                fetchNotifications();
            });

            function fetchNotifications() {
                fetch('/fetchNotifications')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Fetched data:', data); // Log the fetched data
                        const notificationsContainer = document.getElementById('notifications-container');
                        notificationsContainer.innerHTML = '';
                        const notificationCount = document.getElementById('notification-count');
                        notificationCount.innerHTML = data.unread_count;

                        if (data.unread_count > 0) {
                            const markAllReadForm = `
                        <form action="${markAllReadRoute}" method="POST">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <button type="submit" class="btn btn-success" style="font-size: 0.6rem;">
                                Mark all as read
                            </button>
                        </form>
                        <hr style="margin: 6px">
                    `;
                            notificationsContainer.innerHTML += markAllReadForm;
                        }

                        if (data.notifications.length === 0) {
                            notificationsContainer.innerHTML += '<p>No notifications.</p>';
                        } else {
                            data.notifications?.data.forEach(notification => {
                                const notificationHtml = `
                            <div id="notification-${notification.id}">
                                <p style="margin-bottom: 0px">
                                    Comment submitted by
                                    <strong>${notification.data.comment_user}</strong>:
                                    ${notification.data.comment_text}
                                </p>
                                <small>At: ${notification.created_at}</small>

                                ${notification.read_at === null ? `
                                                                                                                                                                                                <a href="${readNotificationRoute.replace('PLACEHOLDER', notification.id)}" class="btn btn-success" style="font-size: 0.6rem">
                                                                                                                                                                                                        Read
                                                                                                                                                                                                 </a>
                                                                                                                                                                                         ` : ''}
                            </div>
                            <hr style="margin: 6px">
                        `;
                                notificationsContainer.innerHTML += notificationHtml;
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error); // Log any fetch errors
                    });
            }
        </script>
    @endif

</head>

<body>
    <div>
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">

                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                        <!-- Notification Icon -->
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="#" data-bs-toggle="modal"
                                data-bs-target="#notificationModal">
                                <i class="bi bi-bell"></i>
                                @auth
                                    {{-- @if (auth()->user()->unreadNotifications->count() > 0) --}}
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        id="notification-count">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                        <span class="visually-hidden">unread notifications</span>
                                    </span>
                                    {{-- @endif --}}
                                @endauth
                            </a>
                        </li>

                        <!-- Modal for Notifications -->
                        <div class="modal fade" id="notificationModal" tabindex="-1"
                            aria-labelledby="notificationModalLabel" aria-hidden="true">
                            <div class="modal-dialog position-absolute" style="top: 30px; right: 1%;">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @auth
                                            <div id="notifications-container">
                                            </div>
                                        @endauth
                                    </div>

                                </div>
                            </div>
                        </div>

                        <li class="nav-item">
                            @auth
                                <a class="nav-link position-relative" href="{{ route('chat.index') }}">
                                    <i class="bi bi-messenger"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        id="message-count">
                                        0
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </a>
                            @endauth
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
        <div class="container py-3">
            <div class="align-items-center d-flex justify-content-center">
                @yield('main-section')
            </div>
        </div>
    </div>
</body>

</html>
