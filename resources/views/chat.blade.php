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

        .typing-indicator {
            background-color: #e6e7ed;
            padding: 8px 16px;
            border-radius: 20px;
            display: none;
            width: fit-content;
            margin: 5px 0;
        }

        .typing-indicator span {
            height: 8px;
            width: 8px;
            float: left;
            margin: 0 1px;
            background-color: #9E9EA1;
            display: block;
            border-radius: 50%;
            opacity: 0.4;
        }

        .typing-indicator span:nth-of-type(1) {
            animation: 1s blink infinite .3333s;
        }

        .typing-indicator span:nth-of-type(2) {
            animation: 1s blink infinite .6666s;
        }

        .typing-indicator span:nth-of-type(3) {
            animation: 1s blink infinite .9999s;
        }

        @keyframes blink {
            50% {
                opacity: 1;
            }
        }

        .message.sent .tick-icon {
            margin-left: 6px;
            color: rgb(1, 9, 1);
            font-size: 0.55em;
        }
    </style>
</head>
@vite('resources/js/app.js')

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
                        <div class="typing-indicator" id="typing">
                            <span class="text-red"></span>
                            <span class="text-red"></span>
                            <span class="text-red"></span>
                        </div>


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
    @if (Auth::user())
        <script>
            function broadcastTypingStatus(isTyping) {

                var recipientId = document.getElementById("recipient-id").value;
                console.log('Broadcasting typing status:', isTyping);
                fetch("{{ route('chat.typingStatus') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        recipient_id: recipientId,
                        is_typing: isTyping
                    })
                });
            }
            document.getElementById("message-input").addEventListener("input", function() {
                broadcastTypingStatus(true);
            });



            document.addEventListener("DOMContentLoaded", function() {
                const userItems = document.querySelectorAll(".user-item");
                const chatMessages = document.getElementById("chat-messages");
                const chatForm = document.getElementById("chat-form");
                const messageInput = document.getElementById("message-input");
                const recipientIdInput = document.getElementById("recipient-id");


                function scrollToBottom() {
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                }


                userItems.forEach(item => {
                    item.addEventListener("click", function() {
                        userItems.forEach(user => user.classList.remove("active"));
                        this.classList.add("active");

                        const userId = this.getAttribute("data-user-id");
                        recipientIdInput.value = userId;
                        loadMessages(userId);
                    });
                });


                Echo.private(`chat.{{ auth()->id() }}`)
                    .listen('MessageSentEvent', (event) => {
                        console.log('Message received:', event);
                        const activeRecipientId = recipientIdInput.value;
                        // Only show message if from currently selected user
                        if (event.message.sender_id == activeRecipientId) {
                            appendMessage(event.message.content, false);
                            scrollToBottom();
                        }
                    });

                Echo.private(`messages.{{ auth()->id() }}`)
                    .listen('MessageDeliveredEvent', (event) => {
                        const messageElement = document.querySelector(
                            `.message[data-message-id="${event.message.id}"]`);
                        if (messageElement) {
                            updateMessageStatus(messageElement);
                        }
                    });


                chatForm.addEventListener("submit", function(event) {
                    event.preventDefault();
                    const formData = new FormData(chatForm);
                    fetch("{{ route('chat.send') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                            body: formData
                        }).then(response => response.json())
                        .then(data => {
                            messageInput.value = "";
                            // Always show sent message in sender's chat
                            appendMessage(data.content, true);
                            scrollToBottom();
                        });
                });

                function loadMessages(userId) {
                    fetch(`/chat/messages/${userId}`)
                        .then(response => response.json())
                        .then(messages => {
                            chatMessages.innerHTML = "";
                            messages.forEach(message => {
                                // Only show messages between current user and selected user
                                if (message.sender_id == {{ auth()->id() }} || message.sender_id ==
                                    userId) {
                                    appendMessage(message.content, message.sender_id ==
                                        {{ auth()->id() }});
                                }
                            });
                            scrollToBottom();
                        });
                }


                function appendMessage(content, isSent) {
                    const messageElement = document.createElement("div");
                    messageElement.classList.add("message", isSent ? "sent" : "received");

                    const messageContent = document.createElement("span");
                    messageContent.innerText = content;
                    messageElement.appendChild(messageContent);

                    const statusIcon = document.createElement("span");
                    statusIcon.classList.add("status-icon");
                    if (isSent) {
                        statusIcon.innerHTML = "&#10003;&#10003;";
                        statusIcon.classList.add("tick-icon");
                    }
                    messageElement.appendChild(statusIcon);

                    chatMessages.appendChild(messageElement);
                    return messageElement;
                }

                function updateMessageStatus(messageElement) {
                    const statusIcon = messageElement.querySelector(".status-icon");
                    if (statusIcon) {
                        statusIcon.innerHTML = "&#10003;&#10003;";
                        statusIcon.classList.add("tick-icon");
                    }
                }

                let currentTypingChannel;

                function showTypingIndicator() {
                    const typingDiv = document.getElementById('typing');
                    typingDiv.style.display = 'block';
                    setTimeout(() => {
                        typingDiv.style.display = 'none';
                    }, 3000);
                }

                // When selecting a user to chat with
                userItems.forEach(item => {
                    item.addEventListener("click", function() {
                        const recipientId = this.getAttribute("data-user-id");

                        if (currentTypingChannel) {
                            Echo.leave(currentTypingChannel);
                        }

                        Echo.private(`type.{{ auth()->id() }}`)
                            .listen('.user.typing', (event) => {

                                showTypingIndicator();
                            });
                    });
                });
            });
        </script>
    @endif

</body>

</html>
