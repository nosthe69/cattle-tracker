<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cattle Tracker</title>
    <link rel="stylesheet" href="style.css">

    <script type="module" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@5.4.0/dist/ionicons/ionicons.js"></script>
        
</head>
<body>
    <div class="main">
        <div class="navbar">
            <div class="icon">
                <h2 class="logo">CattleTracker</h2>
            </div>
            <div class="menu">
                <ul>
                    <li><a href="#">HOME</a></li>
                    <li><a href="#about-section">ABOUT</a></li>
                    <li><a href="#contact">CONTACT</a></li>
                    <li><a href="#" id="login-btn">LOGIN</a></li>
                </ul>
            </div>
        </div>
        <div class="content">
            <h1>Innovative Solutions<br> for Rural Cattle<br> Tracking</h1>
            <p class="par">Join our community of farmers and ranchers dedicated to <br> using technology for smarter and safer cattle tracking.<br> Register, track, and protect your livestock effortlessly.</p>
            <button class="cn"><a href="#" id="join-us-btn">JOIN US</a></button>

            <div class="form" id="login-form" style="display: none;">
              <h2>Login Here</h2>
              <form action="login.php" method="POST">
                  <input type="email" name="email" placeholder="Enter Email Here" required>
                  <input type="password" name="password" placeholder="Enter Password Here" required>
                  <button class="btn" type="submit">Login</button>
          
                  <p class="link">Don't have an account?<br>
                  <a href="#" id="show-signup">Sign Up</a></p>
                  <p class="link"><a href="forgot_password.php">Forgot Password?</a></p> <!-- Added Forgot Password link -->
                  <p class="liw">Log in with:</p>
          
                  <div class="icon">
                      <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                      <a href="#"><ion-icon name="logo-google"></ion-icon></a>
                      <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                  </div>
              </form>
            </div>
          
            <div class="form" id="signup-form" style="display:none;">
              <h2>Sign Up Here</h2>
              <form action="signup.php" method="POST">
                  <div class="row">
                      <input type="text" name="first-name" placeholder="Enter First Name" required>
                      <input type="text" name="last-name" placeholder="Enter Last Name" required>
                  </div>
                  
                  <input type="email" name="email" placeholder="Enter Email" required>
                  
                  <input type="password" name="password" placeholder="Enter Password" required>
                  <input type="password" name="confirm-password" placeholder="Confirm Password" required>
                  
                  <button class="btn" type="submit">Sign Up</button>
                  
                  <p class="link">Already have an account?<br>
                      <a href="#" id="show-login">Login</a>
                  </p>
                  
                  <p class="liw">Sign up with:</p>
                  
                  <div class="icon">
                      <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                      <a href="#"><ion-icon name="logo-google"></ion-icon></a>
                      <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                  </div>
              </form>
            </div>

            <script src="scripts.js"></script>
        </div>

        <section id="about-section" class="about-section">
            <div class="about-section">
                <h1>About Us</h1>
                <p>Welcome to CattleTracker! Our application is designed to help cattle owners and livestock farmers easily manage and track their cattle using modern technology. Whether you need to keep track of your cattle’s information, report lost or found cattle, or manage cattle-related data, our app simplifies the process.</p>
                <p>Resize the browser window to see that this page is responsive by the way.</p>
              </div>
              
              <h2 style="text-align:center">Our Team</h2>
              <div class="row">
                <div class="column">
                  <div class="card">
                    <img src="zoi - Copy.png" alt="Mike" style="width:100%">
                    <div class="container">
                      <h2>Zoyisile Ngwadla</h2>
                      <p class="title">Founder</p>
                      <p>A tech enthusiast who grew up in rural KwaZulu-Natal.</p>
                      <p>zoyisilengwadla@gmail.com</p>
                      <p><button class="button">Contact</button></p>
                    </div>
                  </div>
                </div>
              </div>
        </section>

        <section id="contact" class="contact">
          <h1>Contact Us</h1>
          <p>Get in touch with us for any inquiries, support, or feedback.</p>
          <div class="contact-details">
              <p><strong>Phone:</strong> +27 123 456 789</p>
              <p><strong>Email:</strong> info@cattletracker.com</p>
              <p><strong>Address:</strong> 123 Farm Road, Kimberley, South Africa</p>
          </div>
        </section>

    </div>
</body>
