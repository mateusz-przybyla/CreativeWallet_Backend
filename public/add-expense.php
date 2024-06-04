<?php
session_start();

if (!isset($_SESSION['logged_id'])) {
  header('Location: ../index.php');
  exit();
} else {
  if (isset($_POST['amount'])) {
    $isCorrect = true;

    $rawAmount = filter_input(INPUT_POST, 'amount');
    $amount = number_format($rawAmount, 2, ".", "");

    if (!is_numeric($amount) || ($amount <= 0)) {
      $isCorrect = false;
      $_SESSION['e_amount'] = "Amount must be greater than 0";
    }

    echo "Kwota: " . $amount . "<br>";

    $date = filter_input(INPUT_POST, 'date');

    function validateDate($date, $format)
    {
      $d = DateTime::createFromFormat($format, $date);
      return $d && $d->format($format) == $date;
    }

    $today = date('Y-m-d');
    echo "Dzisiaj: " . $today . "<br>";

    if (!validateDate($date, 'Y-m-d') || ($date > $today) || ($date < '2000-01-01')) {
      $isCorrect = false;
      $_SESSION['e_date'] = "Select a date between today and 2000-01-01";
    }

    echo "Data: " . $date . "<br>";

    $payment = filter_input(INPUT_POST, 'payment');

    if (empty($payment)) {
      $isCorrect = false;
      $_SESSION['e_payment'] = "Please choose a payment method";
    }

    $category = filter_input(INPUT_POST, 'category');

    if (empty($category)) {
      $isCorrect = false;
      $_SESSION['category'] = "Please choose an expense category";
    }

    $comment = filter_input(INPUT_POST, 'comment');

    if (strlen($comment) > 100) {
      $isCorrect = false;
      $_SESSION['e_comment'] = "Comment must be between 0 and 100 characters long";
    }

    echo "Payment: " . $payment . "<br>";
    echo "Category: " . $category . "<br>";
    echo "Comment: " . $comment . "<br>";

    if ($isCorrect) {
      require_once '../database.php';

      $userId = $_SESSION['logged_id'];
      echo "userId: " . $userId . "<br>";

      $query = $db->prepare('SELECT `id` FROM `payment_methods_assigned_to_users` WHERE `user_id` = :userId AND `name` = :payment');
      $query->bindValue(':userId', $userId, PDO::PARAM_INT);
      $query->bindValue(':payment', $payment, PDO::PARAM_STR);
      $query->execute();

      $assignedPaymentMethod = $query->fetch();
      $paymentMethodId = $assignedPaymentMethod['id'];

      echo "paymentMethodId: " . $paymentMethodId . "<br>";

      $query = $db->prepare('SELECT `id` FROM `expenses_category_assigned_to_users` WHERE `user_id` = :userId AND `name` = :category');
      $query->bindValue(':userId', $userId, PDO::PARAM_INT);
      $query->bindValue(':category', $category, PDO::PARAM_STR);
      $query->execute();

      $assignedExpenseCategory = $query->fetch();
      $expenseCategoryId = $assignedExpenseCategory['id'];

      echo "expenseCategoryId: " . $expenseCategoryId . "<br>";

      $query = $db->prepare('INSERT INTO `expenses` VALUES (NULL, :user_id, :expense_category_assigned_to_user_id, :payment_method_assigned_to_user_id, :amount, :date_of_expense, :expense_comment)');
      $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
      $query->bindValue(':expense_category_assigned_to_user_id', $expenseCategoryId, PDO::PARAM_INT);
      $query->bindValue(':payment_method_assigned_to_user_id', $paymentMethodId, PDO::PARAM_INT);
      $query->bindValue(':amount', $amount, PDO::PARAM_STR);
      $query->bindValue(':date_of_expense', $date, PDO::PARAM_STR);
      $query->bindValue(':expense_comment', $comment, PDO::PARAM_STR);
      $query->execute();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Creative Wallet - Add Expense</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div class="bg-cream h-100 pt-2 position-relative">
    <header>
      <nav class="navbar navbar-expand-xl navbar-dark bg-dark mx-2 rounded-3" aria-label="toggle navigation">
        <div class="container">
          <a class="navbar-brand" href="./user-page.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="30" fill="currentColor" class="bi bi-wallet-fill me-1 mb-1" viewBox="0 0 16 16">
              <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542s.987-.254 1.194-.542C9.42 6.644 9.5 6.253 9.5 6a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z" />
              <path d="M16 6.5h-5.551a2.7 2.7 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5s-1.613-.412-2.006-.958A2.7 2.7 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z" />
            </svg>
            CreativeWallet</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse d-xl-flex justify-content-md-end" id="mainmenu">
            <hr class="line mt-3" />
            <ul class="navbar-nav mb-2 mb-md-0">
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link" href="./user-page.php">User page</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link disabled" href="#">Add income</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link active" aria-current="page" href="./add-expense.php">Add expense</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link" href="./show-balance.php">Show balance</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link disabled" href="#">Settings</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link" href="../index.php">Logout</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>
    <main class="pb-60">
      <div class="container my-5">
        <div class="bg-light-red shadow p-5 rounded-3">
          <div class="text-center">
            <h1 class="h3 mb-4">Please enter data for new Expense:</h1>
          </div>
          <div class="row d-flex justify-content-center">
            <form class="col-md-8 col-lg-7 col-xl-6" method="post">
              <div class="mb-3">
                <label for="expenseAmount" class="form-label">Amount</label>
                <div class="input-group">
                  <span class="input-group-text bg-grey-blue rounded-end-0"><img src="../assets/svg/123.svg" alt="amount" width="25" /></span>
                  <input type="number" name="amount" min="1" step="any" class="form-control" id="expenseAmount" required="" />
                </div>
              </div>
              <div class="mb-3">
                <label for="expenseDate" class="form-label">Date</label>
                <div class="input-group">
                  <span class="input-group-text bg-grey-blue rounded-end-0"><img src="../assets/svg/calendar-date.svg" alt="calendar-date" width="25" /></span>
                  <input type="date" name="date" value="" class="form-control" id="expenseDate" required="" />
                </div>
              </div>
              <div class="mb-3">
                <label for="expensePayment" class="form-label">Method payment</label>
                <div class="input-group">
                  <span class="input-group-text bg-grey-blue rounded-end-0"><img src="../assets/svg/credit-card.svg" alt="credit-card" width="25" /></span>
                  <select class="form-select" name="payment" id="expensePayment" required="">
                    <option value="">Choose...</option>
                    <option>Cash</option>
                    <option>Credit card</option>
                    <option>Debit card</option>
                  </select>
                </div>
              </div>
              <div class="mb-3">
                <label for="expenseCategory" class="form-label">Category</label>
                <div class="input-group">
                  <span class="input-group-text bg-grey-blue rounded-end-0"><img src="../assets/svg/tags-fill.svg" alt="tags-fill" width="25" /></span>
                  <select class="form-select" name="category" id="expenseCategory" required="">
                    <option value="">Choose...</option>
                    <option>Food</option>
                    <option>House</option>
                    <option>Transport</option>
                    <option>Telecom</option>
                    <option>Health care</option>
                    <option>Clothing</option>
                    <option>Hygiene</option>
                    <option>Children</option>
                    <option>Entertainment</option>
                    <option>Trip</option>
                    <option>Training courses</option>
                    <option>Books</option>
                    <option>Debt repayment</option>
                    <option>Pension</option>
                    <option>Donation</option>
                    <option>Other expenses</option>
                  </select>
                </div>
              </div>
              <div class="mb-4">
                <label for="expenseComment" class="form-label">Comment (Optional)</label>
                <div class="input-group">
                  <span class="input-group-text bg-grey-blue rounded-end-0"><img src="../assets/svg/chat-dots-fill.svg" alt="chat-dots-fill" width="25" /></span>
                  <textarea class="form-control" name="comment" id="expenseComment" rows="2"></textarea>
                </div>
              </div>
              <div class="container">
                <div class="row d-flex justify-content-between gy-2">
                  <a href="./user-page.html" class="col-sm-3 btn btn-lg btn-danger">
                    Cancel
                  </a>
                  <button class="col-sm-3 btn btn-lg btn-success" type="submit">
                    Add
                  </button>
                </div>
              </div>
            </form>
          </div>
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
  <script src="../add-expense.js" type="text/javascript"></script>
</body>

</html>