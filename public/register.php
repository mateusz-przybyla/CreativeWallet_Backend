<?php
session_start();

if (isset($_POST['email'])) {
  $isCorrect = true;

  $username = filter_input(INPUT_POST, 'username');

  if ((strlen($username) < 3) || (strlen($username) > 20)) {
    $isCorrect = false;
    $_SESSION['e_username'] = "Username must be between 3 and 20 characters long";
  }

  if (ctype_alnum($username) == false) {
    $isCorrect = false;
    $_SESSION['e_username'] = "Username can contain any letters from a to z and any numbers from 0 to 9";
  }

  $password = filter_input(INPUT_POST, 'password');
  $confirmedPassword = filter_input(INPUT_POST, 'confirmed_password');

  if ((strlen($password) < 8) || (strlen($password) > 20)) {
    $isCorrect = false;
    $_SESSION['e_password1'] = "Password must be between 8 and 20 characters long";
  }

  if ($password != $confirmedPassword) {
    $isCorrect = false;
    $_SESSION['e_password2'] = "Passwords do not match";
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

  $_SESSION['m_username'] = $username;
  $_SESSION['m_email'] = $email;
  $_SESSION['m_password1'] = $password;
  $_SESSION['m_password2'] = $confirmedPassword;

  if (empty($email)) {
    $isCorrect = false;
    $_SESSION['e_email'] = "Please enter a valid email address";
  } else {
    require_once '../database.php';

    $emailQuery = $db->prepare('SELECT id FROM users WHERE email = :email');
    $emailQuery->bindValue(':email', $email, PDO::PARAM_STR);
    $emailQuery->execute();

    $existEmail = $emailQuery->fetch();

    if ($existEmail) {
      $isCorrect = false;
      $_SESSION['e_email'] = "Account with this email address already exists";
    } else {
      if ($isCorrect) {
        $query = $db->prepare('INSERT INTO users VALUES (NULL, :username, :email, :password)');
        $query->bindValue(':username', $username, PDO::PARAM_STR);
        $query->bindValue(':email', $email, PDO::PARAM_STR);
        $query->bindValue(':password', $hashedPassword, PDO::PARAM_STR);
        $query->execute();

        $_SESSION['success_reg'] = true;
        header('Location: welcome.php');
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Creative Wallet - Register</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div class="bg-cream h-100 pt-2 position-relative">
    <header>
      <nav class="navbar navbar-dark bg-dark px-lg-3 mx-2 rounded-3" aria-label="toggle navigation">
        <div class="container">
          <a class="navbar-brand" href="../index.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="30" fill="currentColor" class="bi bi-wallet-fill me-1 mb-1" viewBox="0 0 16 16">
              <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542s.987-.254 1.194-.542C9.42 6.644 9.5 6.253 9.5 6a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z" />
              <path d="M16 6.5h-5.551a2.7 2.7 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5s-1.613-.412-2.006-.958A2.7 2.7 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z" />
            </svg>
            CreativeWallet</a>
          <a class="btn btn-secondary px-2 gap-3 ms-lg-3" href="../index.php"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrow-90deg-left" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M1.146 4.854a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H12.5A2.5 2.5 0 0 1 15 6.5v8a.5.5 0 0 1-1 0v-8A1.5 1.5 0 0 0 12.5 5H2.707l3.147 3.146a.5.5 0 1 1-.708.708z" />
            </svg>
            Back</a>
        </div>
      </nav>
    </header>
    <main class="pb-75">
      <div class="container my-5">
        <div class="bg-light-red shadow p-5 text-center rounded-3">
          <form class="w-lg-50 mx-auto" method="post">
            <img class="mb-2" src="../assets/svg/person-plus-fill.svg" alt="person-plus-fill" height="70" />
            <h1 class="h3 mb-4">Sign up</h1>
            <div class="d-flex">
              <figure class="d-flex align-items-center rounded-left-3 px-2 mb-1 rounded-start-2 bg-grey-blue border">
                <img src="../assets/svg/person-check.svg" alt="person-chec" height="25" />
              </figure>
              <div class="form-floating mb-1 w-100">
                <input type="text" name="username" value="<?php
                                                          if (isset($_SESSION['m_username'])) {
                                                            echo $_SESSION['m_username'];
                                                            unset($_SESSION['m_username']);
                                                          } ?>" class="form-control rounded-0 rounded-end-2 <?php
                                                                                                            if (isset($_SESSION['e_username'])) {
                                                                                                              echo "is-invalid";
                                                                                                            }
                                                                                                            ?>" id="register-username" placeholder="Username" required="" />
                <label for="register-username">Username</label>
              </div>
            </div>
            <?php
            if (isset($_SESSION['e_username'])) {
              echo '<div class="text-danger text-start small">' . $_SESSION['e_username'] . '</div>';
              unset($_SESSION['e_username']);
            }
            ?>
            <div class="d-flex">
              <figure class="d-flex align-items-center rounded-left-3 px-2 mb-1 mt-2 rounded-start-2 bg-grey-blue border">
                <img src="../assets/svg/envelope.svg" alt="person-fill" height="25" />
              </figure>
              <div class="form-floating mb-1 mt-2 w-100">
                <input type="email" name="email" value="<?php
                                                        if (isset($_SESSION['m_email'])) {
                                                          echo $_SESSION['m_email'];
                                                          unset($_SESSION['m_email']);
                                                        } ?>" class="form-control rounded-0 rounded-end-2 <?php
                                                                                                          if (isset($_SESSION['e_email'])) {
                                                                                                            echo "is-invalid";
                                                                                                          }
                                                                                                          ?>" id="register-email" placeholder="name@example.com" required="" />
                <label for="register-email">Email</label>
              </div>
            </div>
            <?php
            if (isset($_SESSION['e_email'])) {
              echo '<div class="text-danger text-start small">' . $_SESSION['e_email'] . '</div>';
              unset($_SESSION['e_email']);
            }
            ?>
            <div class="d-flex">
              <figure class="d-flex align-items-center rounded-left-3 px-2 mb-1 mt-2 rounded-start-2 bg-grey-blue border">
                <img src="../assets/svg/lock.svg" alt="lock-fill" height="25" />
              </figure>
              <div class="form-floating mb-1 mt-2 w-100">
                <input type="password" name="password" value="<?php
                                                              if (isset($_SESSION['m_password1'])) {
                                                                echo $_SESSION['m_password1'];
                                                                unset($_SESSION['m_password1']);
                                                              } ?>" class="form-control rounded-0 rounded-end-2 <?php
                                                                                                                if (isset($_SESSION['e_password1'])) {
                                                                                                                  echo "is-invalid";
                                                                                                                }
                                                                                                                ?>" id="register-password1" placeholder="Password" required="" />
                <label for="register-password1">Password</label>
              </div>
            </div>
            <?php
            if (isset($_SESSION['e_password1'])) {
              echo '<div class="text-danger text-start small">' . $_SESSION['e_password1'] . '</div>';
              unset($_SESSION['e_password1']);
            }
            ?>
            <div class="d-flex">
              <figure class="d-flex align-items-center rounded-left-3 px-2 mb-1 mt-2 rounded-start-2 bg-grey-blue border">
                <img src="../assets/svg/lock-fill.svg" alt="lock" height="25" />
              </figure>
              <div class="form-floating mb-1 mt-2 w-100">
                <input type="password" name="confirmed_password" value="<?php
                                                                        if (isset($_SESSION['m_password2'])) {
                                                                          echo $_SESSION['m_password2'];
                                                                          unset($_SESSION['m_password2']);
                                                                        } ?>" class="form-control rounded-0 rounded-end-2 <?php
                                                                                                                          if (isset($_SESSION['e_password2'])) {
                                                                                                                            echo "is-invalid";
                                                                                                                          }
                                                                                                                          ?>" id="register-password2" placeholder="Repeat password" required="" />
                <label for="register-password2">Repeat password</label>
              </div>
            </div>
            <?php
            if (isset($_SESSION['e_password2'])) {
              echo '<div class="text-danger text-start small">' . $_SESSION['e_password2'] . '</div>';
              unset($_SESSION['e_password2']);
            }
            ?>
            <button class="w-100 btn btn-lg btn-success mt-3" type="submit">
              Sign up
            </button>
            <p class="pt-3 my-0">
              Already have an account?
              <a href="./login.php" class="text-decoration-none fw-500">Sign in</a>
            </p>
          </form>
        </div>
      </div>
    </main>
    <footer class="position-absolute w-100 bottom-0">
      <div class="bg-grey-blue mx-2 rounded-top-3">
        <div class="container">
          <div class="row d-flex justify-content-between align-items-center">
            <div class="col-md-4 d-flex justify-content-center justify-content-md-start">
              <p class="my-2">© 2024 CreativeWallet</p>
            </div>
            <div class="col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
              <p class="my-2">Author: Mateusz Przybyła</p>
              <a class="text-body-secondary" href="https://github.com/mateusz-przybyla" target="_blank"><img src="../assets/svg/github.svg" alt="graph-up-arrow" height="20" class="ms-3 my-1" /></a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>