<?php

session_start();

if(!isset($_SESSION["user"])){
  header("location: login.php");
}
//如果登出，回到login這一頁

require_once("../db2-connect.php");

$sql = "SELECT * FROM product2 WHERE valid=1";

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// var_dump($rows);
// exit;

if(isset($_GET["search"])){
  $search=$_GET["search"];
  $sql="SELECT * FROM product2 WHERE name LIKE '%$search%' AND valid=1 ORDER BY id DESC";
  $result=$conn->query($sql);
  $userCount=$result->num_rows;

}else{

  if(isset($_GET["page"])){
    $page=$_GET["page"];
  }else{
    $page=1;
  }

    $sqlAll="SELECT * FROM product2 WHERE valid=1 ";
    $resultAll=$conn->query($sqlAll);
    $userCount=$resultAll->num_rows;

  $per_page=8;
  $page_start=($page-1)*$per_page;

  $sql="SELECT * FROM product2 ORDER BY id DESC LIMIT $page_start, $per_page";

  $result=$conn->query($sql);

//計算頁數
$totalPage=ceil($userCount/$per_page);  //無條件進位

}

$rows=$result->fetch_all(MYSQLI_ASSOC);  //關聯式陣列


// var_dump($userCount);
// exit;

?>
<!doctype html>
<html lang="en">

<head>
  <title>product list</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS v5.2.1 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
  <style>
    .object-cover {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    h6 {
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }
    body{
            height: 120vh;
        }
        :root{
            --side-width: 260px;
        }
        .main-nav .form-control{
            background: #444;
            border: none;
            color: #fff;
            border-radius: 0;
        }
        .main-nav .btn{
            border-radius: 0;
        }

        .logo{
            width: var(--side-width);
        }
        .left-aside{
            width: var(--side-width);
            height: 100vh; 
            padding-top: 54px;
            overflow: auto;
        }
        .aside-menu ul a{
            display: block;
            color: #333;
            text-decoration: none;
        }
        .aside-menu a:hover{
            color: #666;
        }
        .aside-menu a i{
            margin-right: 8px;
        }
        .aside-subtitle{
            font-size: 14px;
        }
        .main-content{
            margin-left: calc(var(--side-width) + 20px);
            padding-top: 54px;
        }
  </style>


</head>

<body>
<nav class="main-nav d-flex bg-dark fixed-top shadow">
    <a class="text-nowrap px-3 text-white text-decoration-none d-flex align-items-center logo flex-shrink-0" href="">藝拍</a>
    <input type="text" class="form-control">
    <a class="btn btn-dark text-nowrap" href="logout.php">Sign out</a>
  </nav>
  <aside class="left-aside position-fixed bg-light border-end">
    <nav class="aside-menu">
      <div class="pt-1 px-3 pb-2">
        Welcome <?=$_SESSION["user"]["account"]?> !
      </div>
        <ul class="list-unstyled">
            <!-- <li><a href="" class="px-3 py-2"> <i class="fa-solid fa-gauge fa-fw"></i>Dashboard</a></li>            
            <li><a href="" class="px-3 py-2"><i class="fa-regular fa-file-lines fa-fw"></i>Order</a></li> -->
            <li><a href="../user/users.php" class="px-3 py-2"><i class="fa-solid fa-user"></i>買家管理</a></li>
            <li><a href="../product/product-list2.php" class="px-3 py-2"><i class="fa-solid fa-cart-shopping"></i>藝術品</a></li>
            <!-- <li><a href="" class="px-3 py-2"><i class="fa-solid fa-chart-simple"></i>Reports</a></li>
            <li><a href="" class="px-3 py-2"><i class="fa-solid fa-layer-group"></i>Integrations</a></li> -->
        </ul>
        <!-- <div class="aside-subtitle px-3 text-secondary mb-4 d-flex justify-content-between">SAVED REPORTS <a role="button"><i class="fa-solid fa-plus"></i></a></div>

        <ul class="list-unstyled">
            <li><a href="" class="px-3 py-2"><i class="fa-solid fa-file"></i>Current month</a></li>
            <li><a href="" class="px-3 py-2"><i class="fa-solid fa-file"></i>Last quarter</a></li>
            <li><a href="" class="px-3 py-2"><i class="fa-solid fa-file"></i>Social engagement</a></li>
            <li><a href="" class="px-3 py-2"><i class="fa-solid fa-file"></i>Year-end sale</a></li>
        </ul> -->
    </nav>
  </aside>
  <main class="main-content">
    <div class="d-flex justify-content-between">
        <h1>展覽空間</h1>
        
    </div>

  <div class="container">
    <div class="py-2">
      <form action="product-list2.php" method="get">
        <div class="input-group">
          <input type="text" class="form-control" name="search">
          <button type="submit" class="btn btn-secondary">搜尋</button>
        </div>
      </form>
    </div>

    <?php if(isset($_GET["search"])): ?>
      <div class="py-2">
        <a class="btn btn-secondary" href="product-list2.php">回列表</a>
      </div>
      <h1><?=$_GET["search"]?>的搜尋結果</h1>
    <?php endif; ?>

      <div class="py-2 text-end"> 
        共 <?=$userCount?> 間
      </div>
    <div class="row g-3">
      <?php foreach ($rows as $exhibition) : ?>
        <div class="col-lg-3 col-md-6">
          <div class="card position-relative">
            <h3 class="mt-2 mb-2 text-center h4"><?= $exhibition["name"] ?></h3>
            <figure class="ratio ratio-16x9">
              <img class="object-cover" src="/images/<?= $exhibition["img"] ?>" alt="">

            </figure>
            <div class="px-3 pb-4">
              <h6 class="text-center h6"><?= $exhibition["introduction"] ?></h6>
              <div class="text-end"> <a href="#">詳細介紹</a></div>
              <hr>
              <h6 class="text-center"><?= $exhibition["address"] ?></h6>

            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (!isset($_GET["search"])) : ?>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPage ; $i++) : ?>
              <li class="page-item <?php if ($i == $page) echo "active"; ?>">
              <a class="page-link" href="product-list2.php?page=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>
    </div>
  </div>


<!-- Bootstrap JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"
    integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"
    integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous">
  </script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
     const labels = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
  ];

  const data = {
    labels: labels,
    datasets: [{
      label: 'My First dataset',
      backgroundColor: 'rgb(255, 99, 132)',
      borderColor: 'rgb(255, 99, 132)',
      data: [0, 10, 5, 2, 20, 30, 45],
    }]
  };

  const config = {
    type: 'line',
    data: data,
    options: {}
  };
  const myChart = new Chart(
    document.getElementById('myChart'),
    config
  );
  </script>




</body>

</html>