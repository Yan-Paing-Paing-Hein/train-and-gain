Please continue creating "coach/login_form.php" and "coach/login_action.php". It must be named as "login_form" instead of just "login".

After log in is successful, each specific id coach must lead to "coach/home.php" just like after registration.


(Here is my current html and css design made for "coach/login_form.php". Please don't change or remove these css class names.)
<section class="contact fade-up" id="contact">
    <div class="contact-container">
        <div class="section-header">
            <h2 class="section-title">Log in to your account</h2>
            <p class="section-subtitle">Come train and come gain!</p>
        </div>

        <div class="contact-form-wrapper">
            <form class="contact-form" method="POST" action="login_action.php">

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email"
                        placeholder="useremail@gmail.com"
                        required autocomplete="email">
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password"
                            placeholder="Enter your password"
                            required autocomplete="current-password">
                        <button type="button" id="togglePassword" class="toggle-password">Show</button>
                    </div>
                </div>

                <button type="submit" class="btn-create btn-upload">Log in</button>

            </form>
        </div>

    </div>
</section>