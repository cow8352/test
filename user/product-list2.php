<?php
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
  </style>


</head>

<body>

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
</body>

</html>