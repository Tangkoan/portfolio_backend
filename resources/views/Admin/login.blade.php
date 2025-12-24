<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login System</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .captcha-box {
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI1IiBoZWlnaHQ9IjUiPgo8cmVjdCB3aWR0aD0iNSIgaGVpZ2h0PSI1IiBmaWxsPSIjZmZmIj48L3JlY3Q+CjxyZWN0IHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9IiNjY2MiPjwvcmVjdD4KPC9zdmc+');
            letter-spacing: 5px;
            font-family: 'Courier New', monospace;
            text-decoration: line-through;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Sign In</h2>

        <form id="loginForm" action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username or Email</label>
                <input type="text" name="username" id="username"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Enter username or email">
                
                <p id="error-username" class="text-red-500 text-xs italic mt-1 hidden"></p>
            </div>

            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="passwordInput"
                        class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" 
                        placeholder="********">
                    
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700">
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                        </svg>
                    </button>
                </div>
                <p id="error-password" class="text-red-500 text-xs italic mt-1 hidden"></p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Security Code (Captcha)</label>
                <div class="flex items-center space-x-2 mb-2">
                    <div class="captcha-box w-1/2 bg-gray-200 py-2 text-center text-xl font-bold text-gray-600 rounded select-none">
                        {{ $captchaCode }}
                    </div>
                    <button type="button" onclick="window.location.reload()" class="text-blue-500 text-sm hover:underline">Refresh</button>
                </div>
                <input type="text" name="captcha" id="captcha"
                    class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Enter code above">
                
                <p id="error-captcha" class="text-red-500 text-xs italic mt-1 hidden"></p>
            </div>

            <button type="submit" id="btnSubmit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-200">
                Login
            </button>
        </form>
    </div>

    <script>
        // 1. Eye Icon Function
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // 2. AJAX Submit Function (Prevent Refresh)
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            let formData = new FormData(this);
            let btnSubmit = document.getElementById('btnSubmit');
            
            // Change button to loading state
            btnSubmit.innerHTML = 'Signing in...';
            btnSubmit.disabled = true;

            // Clear old errors
            document.querySelectorAll('[id^="error-"]').forEach(el => {
                el.classList.add('hidden');
                el.innerText = '';
            });

            // Send data to server
            fetch("{{ route('login.submit') }}", {
                method: "POST",
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Login Success -> Redirect
                    window.location.href = data.redirect_url;
                } else {
                    // Login Failed -> Show Errors
                    btnSubmit.innerHTML = 'Login';
                    btnSubmit.disabled = false;

                    if (data.errors) {
                        if (data.errors.username) {
                            showError('error-username', data.errors.username[0]);
                        }
                        if (data.errors.password) {
                            showError('error-password', data.errors.password[0]);
                        }
                        if (data.errors.captcha) {
                            showError('error-captcha', data.errors.captcha[0]);
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btnSubmit.innerHTML = 'Login';
                btnSubmit.disabled = false;
                alert("Connection error! Please try again.");
            });
        });

        // Helper function to show errors
        function showError(elementId, message) {
            let el = document.getElementById(elementId);
            el.innerText = message;
            el.classList.remove('hidden');
        }
    </script>
</body>
</html>