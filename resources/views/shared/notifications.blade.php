@extends('layouts.master')

@section('page_title', 'View Maintenance Work Order')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <style>
        .notifications-panel {
            width: 100%;
            height: 90%;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            font-family: 'Segoe UI', sans-serif;
        }

        .notifications-header {
            padding: 15px;
            background: #128C7E;
            /* WhatsApp green */
            color: white;
            font-weight: bold;
        }

        .notifications-container {
            flex: 1;
            overflow-y: auto;
            scroll-behavior: smooth;
        }

        .notification-item {
            display: flex;
            padding: 12px 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
            cursor: pointer;
        }

        .notification-item:hover {
            background: #f9f9f9;
        }

        .notification-icon {
            margin-right: 12px;
            color: #128C7E;
            font-size: 20px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 4px;
            color: #333;
        }

        .notification-message {
            color: #666;
            font-size: 14px;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notification-time {
            color: #999;
            font-size: 12px;
            float: right;
        }

        /* Custom scrollbar */
        .notifications-container::-webkit-scrollbar {
            width: 6px;
        }

        .notifications-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .notifications-container::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }

        .notifications-container::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }
    </style>
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="col-md-12">
                @include('includes.error')
            </div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Notifications</h3>
                        <ul class="breadcrumb">
                            {{-- <li class="breadcrumb-item"> <a href="{{ route($prevUrl) }}">{{ $crumbHeading }}</a> </li> --}}
                            <li class="breadcrumb-item active">My Notice Board</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">

                        @if ($notifications->count() > 0)
                            <form style="" class="mb-3" action="{{ route('mynotification.clearall') }}"
                                method="POST" onsubmit="return SubmitDelete(this,'Clear My Messages');">
                                @method('delete')
                                @csrf
                                <button class="btn btn-danger btn-sm" type="submit">Clear All</button>
                            </form>
                        @endif

                    </div>

                </div>
            </div>





            {{-- Notification --}}
            {{-- {{ $notifications }} --}}

            <div class="notifications-panel">
                {{-- <div class="notifications-header">
                    <h3>Notifications</h3>
                </div> --}}
                <div class="notifications-container">
                    @foreach ($notifications as $notification)
                        <div class="notification-item">
                            <div class="notification-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="notification-content">
                                <p class="notification-title">{{ $notification->title }}</p>
                                <p class="notification-message">{{ $notification->body }}</p>
                                <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- //Notification --}}




        </div>
    </div>
    </div>
@endsection



@section('js')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.notifications-container');

            // Auto-scroll to bottom when new notifications arrive
            function scrollToBottom() {
                container.scrollTop = container.scrollHeight;
            }

            // Example: Simulate new notification
            setInterval(() => {
                // In a real app, you would use Laravel Echo or polling
                // This is just for demonstration
                //         const notificationItem = document.createElement('div');
                //         notificationItem.className = 'notification-item';
                //         notificationItem.innerHTML = `
            //     <div class="notification-icon">
            //         <i class="fas fa-bell"></i>
            //     </div>
            //     <div class="notification-content">
            //         <p class="notification-title">New Message</p>
            //         <p class="notification-message">You have a new message from John Doe</p>
            //         <span class="notification-time">just now</span>
            //     </div>
            // `;
                container.appendChild(notificationItem);
                scrollToBottom();
            }, 5000); // Remove this in production

            // Initial scroll to bottom
            scrollToBottom();
        });
    </script>
@endsection
