<?php
session_start();

if (!(isset($_SESSION['success_reg']))) {
  header('Location: ../index.php');
  exit();
} else {
  unset($_SESSION['success_reg']);
}

if (isset($_SESSION['m_username'])) unset($_SESSION['m_username']);
if (isset($_SESSION['m_email'])) unset($_SESSION['m_email']);
if (isset($_SESSION['m_password1'])) unset($_SESSION['m_password1']);
if (isset($_SESSION['m_password2'])) unset($_SESSION['m_password2']);

if (isset($_SESSION['e_username'])) unset($_SESSION['e_username']);
if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
if (isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Creative Wallet - Welcome</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div class="bg-cream h-100 pt-2 position-relative">
    <header>
      <nav class="navbar navbar-dark bg-dark mx-2 rounded-3" aria-label="toggle navigation">
        <div class="container">
          <a class="navbar-brand" href="#">
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
          <img src="../assets/svg/check2-square.svg" alt="graph-up-arrow" height="70" class="mb-3" />
          <h1 class="text-body-emphasis py-3 display-5">
            Success!
          </h1>
          <hr />
          <p class="col-lg-8 mx-auto fs-3 py-2">
            <strong>Congratulations, your account has been successfully created.</strong>
          </p>
          <a class="btn btn-success btn-lg px-5" href="login.php">Sign in</a>
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