<?php
session_start();

if (!isset($_SESSION['logged_id'])) {
  header('Location: ../index.php');
  exit();
}

require_once '../database.php';

$userId = $_SESSION['logged_id'];

$query = $db->prepare('SELECT `name`, SUM(`amount`) AS incomeSum FROM  `incomes`, `incomes_category_assigned_to_users` WHERE `incomes`.`income_category_assigned_to_user_id` = `incomes_category_assigned_to_users`.`id`
AND `incomes`.`user_id` = :userId GROUP BY `income_category_assigned_to_user_id` ORDER BY incomeSum DESC');
$query->bindValue(':userId', $userId, PDO::PARAM_INT);
$query->execute();

$incomes = $query->fetchAll();
$totalIncomes = 0;

foreach ($incomes as $income) {
  $totalIncomes += $income['incomeSum'];
}
$_SESSION['total_incomes'] = number_format($totalIncomes, 2, ".", "");

$query = $db->prepare('SELECT `name`, SUM(`amount`) AS expenseSum FROM  `expenses`, `expenses_category_assigned_to_users` WHERE `expenses`.`expense_category_assigned_to_user_id` = `expenses_category_assigned_to_users`.`id`
AND `expenses`.`user_id` = :userId GROUP BY `expense_category_assigned_to_user_id` ORDER BY expenseSum DESC');
$query->bindValue(':userId', $userId, PDO::PARAM_INT);
$query->execute();

$expenses = $query->fetchAll();
$totalExpenses = 0;

foreach ($expenses as $expense) {
  $totalExpenses += $expense['expenseSum'];
}
$_SESSION['total_expenses'] = number_format($totalExpenses, 2, ".", "");

$_SESSION['balance'] = $_SESSION['total_incomes'] - $_SESSION['total_expenses'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Creative Wallet - Show balance</title>

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
                <a class="nav-link" href="./add-income.php">Add income</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link" href="./add-expense.php">Add expense</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link active" aria-current="page" href="#">Show balance</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link disabled" href="#">Settings</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link" href="./logout.php">Logout</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>
    <main class="pb-75">
      <section class="container my-5">
        <div class="bg-light-red shadow py-4 px-2 px-md-5 rounded-3">
          <div class="text-center">
            <h2 class="mb-3">Balance sheet</h2>
            <hr class="" />
          </div>
          <div class="d-flex justify-content-center mb-3">
            <div class="dropdown">
              <button class="btn btn-secondary bg-grey-blue dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Choose time period
              </button>
              <ul class="dropdown-menu">
                <li>
                  <a class="dropdown-item" id="currentMonth" href="#">Current month</a>
                </li>
                <li>
                  <a class="dropdown-item" id="previousMonth" href="#">Previous month</a>
                </li>
                <li>
                  <a class="dropdown-item" id="currentYear" href="#">Current year</a>
                </li>
                <li>
                  <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#balanceModal" id="customPeriod" href="#">Custom period</a>
                </li>
              </ul>
            </div>
          </div>
          <!-- Modal -->
          <div class="modal fade" id="balanceModal" tabindex="-1" aria-labelledby="balanceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title fs-5" id="balanceModalLabel">
                    Choose a date range:
                  </h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="d-flex">
                    <div class="col-6">
                      <p>From:</p>
                      <input type="date" value="" class="form-control me-1" id="balanceFromDate" />
                    </div>
                    <div class="col-6">
                      <p>To:</p>
                      <input type="date" value="" class="form-control ms-1" id="balanceToDate" />
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Close
                  </button>
                  <button type="button" id="saveBtn" class="btn btn-primary" data-bs-dismiss="modal">
                    Save
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center">
            <div class="d-md-flex justify-content-center">
              <p class="fs-5 mb-3 pe-2">Balance sheet for the period:</p>
              <p class="fs-5 lead" id="changePeriod"></p>
            </div>
            <p class="fs-5 lead" id="showDateRange"></p>
          </div>
        </div>
      </section>
      <section class="container my-5">
        <div class="shadow py-4 px-2 px-md-5 bg-light-red rounded-3">
          <div class="text-center">
            <h3 class="mb-3 d-flex justify-content-center align-items-center">
              <img class="me-2" src="../assets/svg/bookmark-check.svg" alt="bookmark-check" height="30" />
              Your score
            </h3>
            <hr class="" />
          </div>
          <div class="text-center">
            <?php
            echo $_SESSION['balance'] > 0 ? '<p class="fs-5 text-success">
              Congratulations! You manage your finances very well :)
            </p>' : '<p class="fs-5 text-danger">
              Be carefull! You are getting into debt :(
            </p>'
            ?>
            <p class="lead fs-2"><?php if (isset($_SESSION['balance'])) {
                                    echo "Balance: " . number_format($_SESSION['balance'], 2, ",", " ") . " zł";
                                    unset($_SESSION['balance']);
                                  }
                                  ?>
            </p>
          </div>
        </div>
      </section>
      <section class="container my-5">
        <div class="shadow py-4 px-2 px-md-5 bg-light-red rounded-3">
          <div class="row">
            <div class="col-lg-6">
              <table class="table table-striped table-bordered table-hover">
                <caption class="h3">
                  <img class="mb-3" src="../assets/svg/plus-circle-dotted.svg" alt="plus-circle-dotted" height="50" />
                  <p class="h3 mb-3">Incomes by category</p>
                </caption>
                <thead>
                  <tr class="bg-grey-blue">
                    <th scope="col">#</th>
                    <th scope="col">Category</th>
                    <th scope="col">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $incomeIndex = 1;
                  foreach ($incomes as $income) {
                    echo "<tr><th scope='row'>{$incomeIndex}</th><td>{$income['name']}</td><td>{$income['incomeSum']}</td></tr>";
                    $incomeIndex++;
                  }
                  ?>
                  <tr>
                    <td colspan="2" class="text-center">Total incomes</td>
                    <?php
                    echo "<th>{$_SESSION['total_incomes']}</th>";
                    ?>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="col-lg-6">
              <table class="table table-striped table-bordered table-hover">
                <caption class="h3">
                  <img class="mb-3" src="../assets/svg/dash-circle-dotted.svg" alt="dash-circle-dotted" height="50" />
                  <p class="h3 mb-3">Expenses by category</p>
                </caption>
                <thead>
                  <tr class="bg-grey-blue">
                    <th scope="col">#</th>
                    <th scope="col">Category</th>
                    <th scope="col">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $expenseIndex = 1;
                  foreach ($expenses as $expense) {
                    echo "<tr><th scope='row'>{$expenseIndex}</th><td>{$expense['name']}</td><td>{$expense['expenseSum']}</td></tr>";
                    $expenseIndex++;
                  }
                  ?>
                  <tr>
                    <td colspan="2" class="text-center">Total expenses</td>
                    <?php
                    echo "<th>{$_SESSION['total_expenses']}</th>";
                    ?>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
      <section class="container my-5">
        <div class="shadow py-4 px-2 px-md-5 bg-white rounded-3">
          <div class="text-center mb-4">
            <h1 class="h3">Your expenses from the selected period</h1>
            <hr class="" />
          </div>
          <div class="d-flex justify-content-center">
            <div id="chartContainer"></div>
          </div>
        </div>
      </section>
      <div id="scrollToTop"></div>
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
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="../show-balance.js" type="text/javascript"></script>
</body>

</html>