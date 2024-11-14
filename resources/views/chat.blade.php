<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chatbox</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css">
    <style>
        .user-item {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .user-item:hover,
        .user-item.active {
            background-color: #f0f0f0;
        }

        #chat-messages {
            height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 15px;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }

        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 15px;
            max-width: 75%;
            word-break: break-word;
        }

        .message.sent {
            background-color: #0d6efd;
            color: white;
            align-self: flex-end;
        }

        .message.received {
            background-color: #6c757d;
            color: white;
            align-self: flex-start;
        }

        .placeholder-text {
            color: #aaa;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="text-center pb-4">Chatbox</h2>
        <div class="row justify-content-center">
            <!-- User List -->
            <div class="col-md-3">
                <h5>Users</h5>
                <ul class="list-group">
                    @foreach ($users as $user)
                        <li class="list-group-item user-item" data-user-id="{{ $user->id }}">
                            <i class="bi bi-person-circle"></i> {{ $user->name }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Chat Messages -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <strong>Messages</strong>
                    </div>
                    <div class="card-body d-flex flex-column" id="chat-messages">
                        <p class="placeholder-text">Select a user to start chatting</p>
                    </div>
                    <div class="card-footer">
                        <form id="chat-form" action="{{ route('chat.send') }}" method="POST">
                            @csrf
                            <input type="hidden" id="recipient-id" name="recipient_id">
                            <div class="input-group">
                                <input type="text" class="form-control" id="message-input" name="message"
                                    placeholder="Type a message..." required>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const userItems = document.querySelectorAll(".user-item");
            const chatMessages = document.getElementById("chat-messages");
            const chatForm = document.getElementById("chat-form");
            const messageInput = document.getElementById("message-input");
            const recipientIdInput = document.getElementById("recipient-id");

            // Highlight selected user and load messages
            userItems.forEach(item => {
                item.addEventListener("click", function() {
                    userItems.forEach(user => user.classList.remove("active"));
                    this.classList.add("active");

                    const userId = this.getAttribute("data-user-id");
                    recipientIdInput.value = userId;
                    loadMessages(userId);
                });
            });

            // Submit message form
            chatForm.addEventListener("submit", function(event) {
                event.preventDefault();

                const formData = new FormData(chatForm);
                fetch("{{ route('chat.send') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    },
                    body: formData
                }).then(response => response.json()).then(data => {
                    messageInput.value = "";
                    appendMessage(data.content, true);
                    chatMessages.scrollTop = chatMessages
                        .scrollHeight;
                });
            });

            // Load messages function
            function loadMessages(userId) {
                fetch(`/chat/messages/${userId}`)
                    .then(response => response.json())
                    .then(messages => {
                        chatMessages.innerHTML = "";
                        messages.forEach(message => {
                            appendMessage(message.content, message.sender_id == {{ auth()->id() }});
                        });
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    });
            }

            // Append message to chat
            function appendMessage(content, isSent) {
                const messageElement = document.createElement("div");
                messageElement.classList.add("message", isSent ? "sent" : "received");
                messageElement.innerText = content;
                chatMessages.appendChild(messageElement);
            }
        });
    </script>
</body>

</html>
