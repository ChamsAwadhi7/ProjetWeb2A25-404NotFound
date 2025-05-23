<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UI/UX</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
  <link rel="stylesheet" href="style.css">
</head>
<body>
   <div class="container">
      <aside> 
         <div class="top">
           <div class="logo">
             <h2><img style="width: 60px; height: 60px;" src="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" alt=""> <span class="danger">NextStep</span> </h2>
           </div>
           <div class="close" id="close_btn">
            <span class="material-symbols-sharp">
              close
            </span>
           </div>
         </div>
         <!-- end top -->
          <div class="sidebar">
            <a href="index.html" class="active">
              <span class="material-symbols-sharp">grid_view </span>
              <h3>Home</h3>
           </a>
           <a href="#">
              <span class="material-symbols-sharp">person_outline </span>
              <h3>custumers</h3>
           </a>
           <a href="EVENT/BackOffice/Event.php" class="close">
              <span class="material-symbols-sharp">receipt_long </span>
              <h3>Events</h3>
            </a>
           <a href="COURS/cours.php" class="close">
            <span class="material-symbols-sharp">receipt_long </span>
            <h3>Courses</h3>
           </a>
           <a href="STARTUP/BackOffice/startup.php" class="close">
            <span class="material-symbols-sharp">business </span>
            <h3>startups</h3>
           </a>
           <a href="STARTUP/BackOffice/incubator.php" id="incubators-btn">
            <span class="material-symbols-sharp">rocket_launch</span>
            <h3>Incubators</h3>
           </a>
           <a href="logout.php">
              <span class="material-symbols-sharp">logout </span>
              <h3>logout</h3>
           </a>
            <a href="#">
                <span class="material-symbols-sharp">settings</span>
                <h3>Settings</h3>
            </a>
          </div>
      </aside>
      <!-- --------------
        end asid
      -------------------- -->

      <!-- --------------
        start main part
      --------------- -->

      <main>
           <h1>404NotFound</h1>

           <div class="date">
             <input type="date" >
           </div>

        <div class="insights">

           <!-- start seling -->
            <div class="sales">
               <span class="material-symbols-sharp">trending_up</span>
               <div class="middle">

                 <div class="left">
                   <h3>Total Sales</h3>
                   <h1>$25,024</h1>
                 </div>
                  <div class="progress">
                      <svg>
                         <circle  r="30" cy="40" cx="40"></circle>
                      </svg>
                      <div class="number"><p>80%</p></div>
                  </div>

               </div>
               <small>Last 24 Hours</small>
            </div>
           <!-- end seling -->
              <!-- start expenses -->
              <div class="expenses">
                <span class="material-symbols-sharp">local_mall</span>
                <div class="middle">
 
                  <div class="left">
                    <h3>Total Sales</h3>
                    <h1>$25,024</h1>
                  </div>
                   <div class="progress">
                       <svg>
                          <circle  r="30" cy="40" cx="40"></circle>
                       </svg>
                       <div class="number"><p>80%</p></div>
                   </div>
 
                </div>
                <small>Last 24 Hours</small>
             </div>
            <!-- end seling -->
               <!-- start seling -->
               <div class="income">
                <span class="material-symbols-sharp">stacked_line_chart</span>
                <div class="middle">
 
                  <div class="left">
                    <h3>Total Sales</h3>
                    <h1>$25,024</h1>
                  </div>
                   <div class="progress">
                       <svg>
                          <circle  r="30" cy="40" cx="40"></circle>
                       </svg>
                       <div class="number"><p>80%</p></div>
                   </div>
 
                </div>
                <small>Last 24 Hours</small>
             </div>
            <!-- end seling -->

        </div>
       <!-- end insights -->
      <div class="recent_order">
         <h2>Recent Orders</h2>
         <table> 
             <thead>
              <tr>
                <th>Product Name</th>
                <th>Product Number</th>
                <th>Payments</th>
                <th>Status</th>
              </tr>
             </thead>
              <tbody>
                 <tr>
                   <td>Mini USB</td>
                   <td>4563</td>
                   <td>Due</td>
                   <td class="warning">Pending</td>
                   <td class="primary">Details</td>
                 </tr>
                 <tr>
                  <td>Mini USB</td>
                  <td>4563</td>
                  <td>Due</td>
                  <td class="warning">Pending</td>
                  <td class="primary">Details</td>
                </tr>
                <tr>
                  <td>Mini USB</td>
                  <td>4563</td>
                  <td>Due</td>
                  <td class="warning">Pending</td>
                  <td class="primary">Details</td>
                </tr>
                <tr>
                  <td>Mini USB</td>
                  <td>4563</td>
                  <td>Due</td>
                  <td class="warning">Pending</td>
                  <td class="primary">Details</td>
                </tr>
              </tbody>
         </table>
         <a href="#">Show All</a>
      </div>

      </main>
      <!------------------
         end main
        ------------------->

      <!----------------
        start right main 
      ---------------------->
    <div class="right">

<div class="top">
   <button id="menu_bar">
     <span class="material-symbols-sharp">menu</span>
   </button>

   <div class="theme-toggler">
     <span class="material-symbols-sharp active">light_mode</span>
     <span class="material-symbols-sharp">dark_mode</span>
   </div>
    <div class="profile">
       <div class="info">
           <p><b>Babar</b></p>
           <p>Admin</p>
           <small class="text-muted"></small>
       </div>
       <div class="profile-photo">
         <img src="./images/profile-1.jpg" alt=""/>
       </div>
    </div>
</div>

  <div class="recent_updates">
     <h2>Recent Update</h2>
   <div class="updates">
      <div class="update">
         <div class="profile-photo">
            <img src="./images/profile-4.jpg" alt=""/>
         </div>
        <div class="message">
           <p><b>Babar</b> Recived his order of USB</p>
        </div>
      </div>
      <div class="update">
        <div class="profile-photo">
        <img src="./images/profile-3.jpg" alt=""/>
        </div>
       <div class="message">
          <p><b>Ali</b> Recived his order of USB</p>
       </div>
     </div>
     <div class="update">
      <div class="profile-photo">
         <img src="./images/profile-2.jpg" alt=""/>
      </div>
     <div class="message">
        <p><b>Ramzan</b> Recived his order of USB</p>
     </div>
   </div>
  </div>
  </div>


   <div class="sales-analytics">
     <h2>Sales Analytics</h2>

      <div class="item onlion">
        <div class="icon">
          <span class="material-symbols-sharp">shopping_cart</span>
        </div>
        <div class="right_text">
          <div class="info">
            <h3>Onlion Orders</h3>
            <small class="text-muted">Last seen 2 Hours</small>
          </div>
          <h5 class="danger">-17%</h5>
          <h3>3849</h3>
        </div>
      </div>
      <div class="item onlion">
        <div class="icon">
          <span class="material-symbols-sharp">shopping_cart</span>
        </div>
        <div class="right_text">
          <div class="info">
            <h3>Onlion Orders</h3>
            <small class="text-muted">Last seen 2 Hours</small>
          </div>
          <h5 class="success">-17%</h5>
          <h3>3849</h3>
        </div>
      </div>
      <div class="item onlion">
        <div class="icon">
          <span class="material-symbols-sharp">shopping_cart</span>
        </div>
        <div class="right_text">
          <div class="info">
            <h3>Onlion Orders</h3>
            <small class="text-muted">Last seen 2 Hours</small>
          </div>
          <h5 class="danger">-17%</h5>
          <h3>3849</h3>
        </div>
      </div>
   
  

</div>

      <div class="item add_product">
            <div>
            <span class="material-symbols-sharp">add</span>
            </div>
     </div>
</div>


   </div>



   <script src="script.js"></script>
</body>
</html>