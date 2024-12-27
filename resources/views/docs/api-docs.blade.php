<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">API Documentation</h1>

        <div class="alert alert-info">
            <strong>Note:</strong> All API requests must include the following header:
            <pre><code>Accept: application/json</code></pre>
        </div>

        <div class="accordion" id="apiDocsAccordion">
            <!-- Signup -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSignup">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSignup" aria-expanded="true" aria-controls="collapseSignup">
                        Signup
                    </button>
                </h2>
                <div id="collapseSignup" class="accordion-collapse collapse show" aria-labelledby="headingSignup" data-bs-parent="#apiDocsAccordion">
                    <div class="accordion-body">
                        <h5>POST /api/signup</h5>
                        <p>Create a new user account.</p>
                        <h6>Request Body:</h6>
                        <pre><code>{
    "name": "required|string|max:255|min:3|unique:users,name|regex:/^\S*$/",
    "email": "required|email|unique:users,email",
    "password": "required|min:8|confirmed"
}</code></pre>
                        <h6>Validation Details:</h6>
                        <ul>
                            <li><strong>name:</strong> Must be a string between 3 and 255 characters. The name must be unique in the users table and cannot contain spaces (no spaces allowed).</li>
                            <li><strong>email:</strong> Must be a valid email address and unique in the users table.</li>
                            <li><strong>password:</strong> Must be at least 8 characters long and must be confirmed by providing a matching password_confirmation field.</li>
                        </ul>
                        <h6>Response:</h6>
                        <pre><code>{
    "status": true,
    "message": "User created successfully! Verify your Email",
    "user": {...},
    "access_token": "...",
    "token_type": "Bearer"
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Login -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLogin">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLogin" aria-expanded="false" aria-controls="collapseLogin">
                        Login
                    </button>
                </h2>
                <div id="collapseLogin" class="accordion-collapse collapse" aria-labelledby="headingLogin" data-bs-parent="#apiDocsAccordion">
                    <div class="accordion-body">
                        <h5>POST /api/login</h5>
                        <p>Login an existing user.</p>
                        <h6>Request Body:</h6>
                        <pre><code>{
    "name": "required",
    "password": "required|min:8"
}</code></pre>
                        <h6>Validation Details:</h6>
                        <ul>
                            <li><strong>name:</strong> Can be either the username or email address of the user.</li>
                            <li><strong>password:</strong> Must be at least 8 characters long.</li>
                        </ul>
                        <h6>Response:</h6>
                        <pre><code>{
    "status": true,
    "message": "User logged in successfully!",
    "user": {...},
    "access_token": "...",
    "token_type": "Bearer"
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Logout -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLogout">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLogout" aria-expanded="false" aria-controls="collapseLogout">
                        Logout
                    </button>
                </h2>
                <div id="collapseLogout" class="accordion-collapse collapse" aria-labelledby="headingLogout" data-bs-parent="#apiDocsAccordion">
                    <div class="accordion-body">
                        <h5>POST /api/logout</h5>
                        <p>Logout the currently authenticated user.</p>
                        <h6>Response:</h6>
                        <pre><code>{
    "status": true,
    "message": "User logged out successfully!"
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Resend Email Verification -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingResendVerification">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResendVerification" aria-expanded="false" aria-controls="collapseResendVerification">
                        Resend Email Verification
                    </button>
                </h2>
                <div id="collapseResendVerification" class="accordion-collapse collapse" aria-labelledby="headingResendVerification" data-bs-parent="#apiDocsAccordion">
                    <div class="accordion-body">
                        <h5>POST /api/email/resend-verification</h5>
                        <p>Resend the email verification link.</p>
                        <h6>Response:</h6>
                        <pre><code>{
    "status": true,
    "message": "A new verification link has been sent to the email address you provided during registration."
}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Password Reset Request -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingResetPassword">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResetPassword" aria-expanded="false" aria-controls="collapseResetPassword">
                        Request Password Reset
                    </button>
                </h2>
                <div id="collapseResetPassword" class="accordion-collapse collapse" aria-labelledby="headingResetPassword" data-bs-parent="#apiDocsAccordion">
                    <div class="accordion-body">
                        <h5>POST /api/reset-password</h5>
                        <p>Request a password reset link.</p>
                        <h6>Request Body:</h6>
                        <pre><code>{
    "email": "required|email"
}</code></pre>
                        <h6>Validation Details:</h6>
                        <ul>
                            <li><strong>email:</strong> Must be a valid email address.</li>
                        </ul>
                        <h6>Response:</h6>
                        <pre><code>{
    "status": true,
    "message": "Password reset link sent. Please check your email."
}</code></pre>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
