<?php
require_once __DIR__ . '/../../../../Controller/eventController.php';
require_once __DIR__ . '/../../../../Controller/rejoindreController.php';
$eventC = new EventC();
$events = $eventC->listEvents();
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : 'asc'; // Default to 'asc'

// Call the function to get filtered and sorted events
$events = $eventC->searchAndSortEvents($searchTerm, $sortOrder);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Événements - NextStep</title>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <style>
      /* Navbar Styles */
      /* Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            visibility: visible !important;
            opacity: 1 !important;
        }

        body {
            background: url('image/bgimg.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            line-height: 1.6;
        }
      .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 30px;
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            width: 100%;
            z-index: 1000;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            transition: background-color 0.3s ease;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .logo span {
            color: #ff8a00;
        }

        .logo-img {
            height: 40px;
            margin-right: 10px;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin: 0 10px;
            position: relative;
        }

        .dropbtn {
            background: none;
            border: none;
            color: #333;
            font-size: 16px;
            cursor: pointer;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .dropbtn:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1;
            list-style: none;
            padding: 10px 0;
        }

        .dropdown-menu li a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-menu li a:hover {
            background-color: #f5f5f5;
            color: #4a6bff;
        }

        .dropdown-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }

        .search-box {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 30px;
            padding: 5px 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .search-box input {
            border: none;
            outline: none;
            padding: 8px;
            width: 200px;
        }

        .search-category {
            border: none;
            outline: none;
            background: none;
            cursor: pointer;
            margin-left: 5px;
            padding-left: 5px;
            border-left: 1px solid #eee;
        }

        .login-btn {
            background-color: #4a6bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .login-btn:hover {
            background-color: #3a5bef;
            transform: translateY(-2px);
        }

        /* Enhanced Event Page Styles */
        .hero-video-section {
            position: relative;
            height: 70vh;
            overflow: hidden;
            margin-bottom: 3rem;
        }
        
        .hero-video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(0.7);
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 0 2rem;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
            animation: fadeInDown 1s ease;
        }
        
        .hero-subtitle {
            font-size: 1.5rem;
            max-width: 800px;
            text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
            animation: fadeInUp 1s ease;
        }
        
        .search-sort-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .event-filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: center;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        .input-group {
            display: flex;
            align-items: center;
            background: white;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            box-shadow: 0 2px 6px rgba(0,0,0,0.05);
            flex: 1 1 300px;
        }
        
        .input-group i {
            color: #6c757d;
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }
        
        .search-field, .sort-dropdown {
            border: none;
            outline: none;
            width: 100%;
            padding: 0.5rem 0;
            font-size: 1rem;
            background: transparent;
        }
        
        .sort-dropdown {
            cursor: pointer;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #3a7bd5, #00d2ff);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem 3rem;
        }
        
        .container h1 {
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 2.5rem;
            color: #2c3e50;
            position: relative;
        }
        
        .container h1:after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #3a7bd5, #00d2ff);
            margin: 0.5rem auto 0;
            border-radius: 2px;
        }
        
        /* Style général pour la section événements */
        .events-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2.5rem;
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* Version responsive */
@media (max-width: 1200px) {
    .events-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .events-grid {
        grid-template-columns: 1fr;
    }
}

/* Style pour quand il n'y a pas d'événements */
.no-events {
    text-align: center;
    padding: 4rem;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 20px;
    width: 100%;
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    font-size: 1.2rem;
    color: #4a4a4a;
    font-weight: 500;
}

/* Carte d'événement - Style moderne */
.event-card {
    position: relative;
    width: 350px;
    height: 450px;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
    transform-style: preserve-3d;
    background: #fff;
}

.event-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0,0,0,0.15);
}

/* Image de l'événement */
.event-image {
    width: 100%;
    height: 60%;
    object-fit: cover;
    transition: all 0.5s ease;
    filter: brightness(0.9);
}

.event-card:hover .event-image {
    height: 50%;
    filter: brightness(1);
}

/* Contenu de la carte */
.event-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1.5rem;
    background: white;
    transition: all 0.5s ease;
    height: 40%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    background: rgba(255, 255, 255, 0.1); /* Fond légèrement transparent */
}

.event-card:hover .event-content {
    height: 50%;
    background: linear-gradient(to bottom, rgba(255,255,255,0.9) 0%, rgba(255,255,255,1) 100%);
}

.event-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0 0 0.5rem 0;
    color: #2c3e50;
    transition: all 0.3s ease;
}

.event-card:hover .event-title {
    color: #3498db;
}

.event-date {
    font-size: 0.9rem;
    color: #7f8c8d;
    margin-bottom: 1rem;
    display: inline-block;
    padding: 0.3rem 0.6rem;
    background: #f1f1f1;
    border-radius: 50px;
    font-weight: 500;
}

/* Bouton de détail */
.btn-detail {
    background: linear-gradient(45deg, #3498db, #2ecc71);
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
    align-self: flex-start;
    margin-top: auto;
}

.btn-detail:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(46, 204, 113, 0.4);
    background: linear-gradient(45deg, #2ecc71, #3498db);
}

/* Effet de vague décoratif */
.event-card::before {
    content: '';
    position: absolute;
    bottom: 40%;
    left: 0;
    width: 100%;
    height: 20%;
    background: white;
    clip-path: ellipse(75% 100% at 50% 100%);
    z-index: 1;
    transition: all 0.5s ease;
}

.event-card:hover::before {
    bottom: 50%;
    clip-path: ellipse(50% 100% at 50% 100%);
}

/* Animation d'entrée */
[data-aos="fade-up"] {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.6s ease;
}

[data-aos="fade-up"].aos-animate {
    opacity: 1;
    transform: translateY(0);
}

/* Style pour la pagination */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
    gap: 0.5rem;
}

.pagination a, .pagination span {
    padding: 0.5rem 1rem;
    border-radius: 50%;
    background: #f1f1f1;
    color: #2c3e50;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background: #3498db;
    color: white;
}

.pagination .current {
    background: #3498db;
    color: white;
    font-weight: bold;
}
        /* Modal Enhancements */
        #eventModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.7);
            z-index: 1000;
            overflow-y: auto;
        }
        
        #modalContent {
            background: white;
            max-width: 700px;
            width: 90%;
            margin: 5vh auto;
            padding: 2rem;
            border-radius: 12px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.4s ease;
        }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #closeModal {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 1.8rem;
            color: #6c757d;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        #closeModal:hover {
            color: #dc3545;
        }
        
        #modalImage {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        #modalTitle {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        #modalContent p {
            margin-bottom: 0.8rem;
            font-size: 1rem;
            color: #495057;
            line-height: 1.6;
        }
        
        #modalContent p strong {
            color: #2c3e50;
        }
        
        .btn-participate {
            display: block;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-align: center;
            margin-top: 1.5rem;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-participate:hover {
            background: linear-gradient(135deg, #218838, #17a2b8);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .events-grid {
                grid-template-columns: 1fr;
            }
            
            .event-filter-form {
                flex-direction: column;
                align-items: stretch;
            }
            
            .input-group {
                width: 100%;
            }
            
            .submit-btn {
                width: 100%;
                justify-content: center;
            }
            
            #modalContent {
                width: 95%;
                padding: 1.5rem;
            }
            
            #modalImage {
                height: 200px;
            }
            
            #modalTitle {
                font-size: 1.8rem;
            }
        }
        
        /* Animation Keyframes */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        /* Footer Styles */
        .footer {
            background-color: #24262b;
            padding: 70px 0;
            color: white;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .footer-col {
            width: 25%;
            padding: 0 15px;
        }

        .footer-col h4 {
            font-size: 18px;
            margin-bottom: 30px;
            position: relative;
        }

        .footer-col h4::before {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            background-color: #4a6bff;
            height: 2px;
            box-sizing: border-box;
            width: 50px;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            color: #ddd;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-col ul li a:hover {
            color: #4a6bff;
            padding-left: 5px;
        }

        .social-links a {
            display: inline-block;
            height: 40px;
            width: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            margin-right: 10px;
            text-align: center;
            line-height: 40px;
            border-radius: 50%;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: #24262b;
            background-color: white;
        }

        /* Responsive Design */
        @media (max-width: 992px) {
            .footer-col {
                width: 50%;
                margin-bottom: 30px;
            }
            
            .floating-image {
                width: 120px;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                padding: 15px;
            }
            
            .nav-links {
                flex-direction: column;
                width: 100%;
                margin-top: 15px;
            }
            
            .search-box {
                margin: 15px 0;
                width: 100%;
            }
            
            .footer-col {
                width: 100%;
            }
            
            .floating-image {
                display: none;
            }
            
            .content h3 {
                font-size: 2rem;
            }
        }
        .hero-event-section {
        position: relative;
        height: 80vh;
        overflow: hidden;
        margin-bottom: 3rem;
    }
    
    .hero-event-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        animation: zoomInOut 20s infinite alternate;
        filter: brightness(0.7);
    }
    
    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        padding: 0 2rem;
    }
    
    .event-highlight {
        max-width: 800px;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(10px);
        padding: 3rem;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .event-badge {
        display: inline-block;
        background: linear-gradient(90deg, #ff8a00, #ff0058);
        padding: 0.5rem 1.5rem;
        border-radius: 30px;
        font-weight: bold;
        margin-bottom: 1.5rem;
        animation: pulse 2s infinite;
    }
    
    .hero-title {
        font-size: 3.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.6);
    }
    
    .event-meta {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.1rem;
    }
    
    .hero-subtitle {
        font-size: 1.3rem;
        line-height: 1.6;
        margin-bottom: 2rem;
    }
    
    .hero-cta {
        background: linear-gradient(135deg, #4a6bff, #3a5bef);
        color: white;
        border: none;
        padding: 1rem 2.5rem;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        box-shadow: 0 5px 15px rgba(74, 107, 255, 0.4);
    }
    
    .hero-cta:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(74, 107, 255, 0.6);
        background: linear-gradient(135deg, #3a5bef, #2a4bdf);
    }
    
    @keyframes zoomInOut {
        0% { transform: scale(1); }
        100% { transform: scale(1.1); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 138, 0, 0.7); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(255, 138, 0, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 138, 0, 0); }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.2rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .event-highlight {
            padding: 2rem 1.5rem;
        }
        
        .event-meta {
            flex-direction: column;
            gap: 0.8rem;
        }
    }
    /* Notification Styles */
    .notification-floater {
    position: fixed;
    bottom: 20px; /* Changed from top to bottom */
    right: 20px;
    z-index: 1000;
}


        .notification-btn {
            background: #4a6bff;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .notification-btn:hover {
            background: #3a5bef;
            transform: scale(1.1);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 12px;
            font-weight: bold;
        }

        .notification-panel {
    display: none;
    position: absolute;
    bottom: 50px; /* Position it above the button (adjust as needed) */
    right: 0;
    background: white;
    width: 350px;
    border-radius: 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    z-index: 1000;
}


        .notification-panel.show {
            display: block;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }

        .notification-header h3 {
            font-size: 18px;
            color: #2c3e50;
        }

        .close-panel {
            background: none;
            border: none;
            font-size: 20px;
            color: #6c757d;
            cursor: pointer;
        }

        .close-panel:hover {
            color: #dc3545;
        }

        .notification-body {
            max-height: 400px;
            overflow-y: auto;
            padding: 10px 20px;
        }

        .notification-category {
            margin-bottom: 20px;
        }

        .notification-category h4 {
            font-size: 16px;
            color: #2c3e50;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .warning-icon {
            color: #ff8a00;
        }

        .notification-alert {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .event-alert {
            background: #e6f3ff;
            border-left: 4px solid #4a6bff;
        }

        .event-alert:hover {
            background: #d9eaff;
        }

        .alert-content {
            flex: 1;
        }

        .alert-title {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .alert-desc {
            font-size: 13px;
            color: #6c757d;
        }

        .notification-alert i {
            color: #6c757d;
        }

        .no-notifications {
            text-align: center;
            padding: 20px;
            color: #6c757d;
        }

        .no-notifications i {
            font-size: 30px;
            color: #28a745;
            margin-bottom: 10px;
        }

        .notification-footer {
            padding: 10px 20px;
            border-top: 1px solid #eee;
            text-align: center;
        }

        .mark-all-read {
            color: #4a6bff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .mark-all-read:hover {
            color: #3a5bef;
        }

        .animate-alert {
            animation: fadeInUp 0.5s ease forwards;
        }
    </style>
</head>
<body>
<nav class="navbar">
        <div class="logo">
            <img src="image/27b64a1f-1d13-458c-8230-3fbaa299beae-removebg.png" alt="Logo" class="logo-img" />
            Next<span>Step</span>
        </div>
        <ul class="nav-links">
            <li class="dropdown">
                <button class="dropbtn">
                    Explore Opportunities <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="fas fa-lightbulb"></i> Innovative Projects</a></li>
                    <li><a href="#"><i class="fas fa-users"></i> Collaborative Ventures</a></li>
                    <li><a href="#"><i class="fas fa-dollar-sign"></i> Funding Opportunities</a></li>
                    <li><a href="#"><i class="fas fa-handshake"></i> Partnerships</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">
                    Our Courses <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="coursF.html"><i class="fas fa-book"></i> Courses</a></li>
                    <li><a href="#"><i class="fas fa-rocket"></i> Entrepreneurship Basics</a></li>
                    <li><a href="#"><i class="fas fa-chart-line"></i> Business Strategies</a></li>
                    <li><a href="#"><i class="fas fa-lightbulb"></i> Innovation Workshops</a></li>
                    <li><a href="#"><i class="fas fa-user-tie"></i> Leadership Programs</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">
                    Our Events <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="eventsF.php"><i class="fas fa-calendar-alt"></i> Our Events</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">
                    Incubator <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="incubator.html#nitro-section"><i class="fas fa-bolt"></i> Nitro Plans</a></li>
                    <li><a href="incubator.html#workspace-section"><i class="fas fa-chair"></i> Working Space</a></li>
                    <li><a href="incubator.html#workshop-section"><i class="fas fa-chalkboard-teacher"></i> Workshops</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">
                    Startup <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="startup.html"><i class="fas fa-cogs"></i> Startup</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <button class="dropbtn">
                    Why Us <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#"><i class="fas fa-cogs"></i> How It Works</a></li>
                    <li><a href="#"><i class="fas fa-trophy"></i> Success Stories</a></li>
                    <li><a href="#"><i class="fas fa-tags"></i> Pricing</a></li>
                    <li><a href="#"><i class="fas fa-question-circle"></i> FAQ</a></li>
                </ul>
            </li>
        </ul>
        <div class="search-box">
            <input type="text" placeholder="Search..." />
            <select class="search-category">
                <option value="project">🔍 Project</option>
                <option value="startup">🚀 Startup</option>
            </select>
        </div>
        <button class="login-btn"><i class="fas fa-user"></i> Log In</button>
    </nav>

    <?php
// Récupérer le dernier événement (ajoutez cette requête dans votre controller)
$lastEvent = $eventC->getLastAddedEvent();
?>

<section class="hero-event-section">
    <?php if ($lastEvent): ?>
        <?php
        $defaultImage = '/4Validation/View/EVENT/BackOffice/uploads/events/default-event.jpg';
        $imageFile = !empty($lastEvent['img_event']) ? basename($lastEvent['img_event']) : 'default-event.jpg';
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/4validation/View/EVENT/BackOffice/uploads/events/' . $imageFile;
        $webPath = '/4Validation/View/EVENT/BackOffice/uploads/events/' . $imageFile;
        
        if (!file_exists($fullPath)) {
            $webPath = $defaultImage;
        }
        ?>
        
        <div class="hero-event-image" style="background-image: url('<?= htmlspecialchars($webPath) ?>');"></div>
        
        <div class="hero-overlay">
            <div class="event-highlight" data-aos="fade-up" data-aos-delay="200">
                <div class="event-badge" data-aos="fade-right" data-aos-delay="400">
                    <i class="fas fa-star"></i> Dernier Événement
                </div>
                
                <h1 class="hero-title" data-aos="fade-up" data-aos-delay="300">
                    <?= htmlspecialchars($lastEvent['nom_event']) ?>
                </h1>
                
                <div class="event-meta" data-aos="fade-up" data-aos-delay="400">
                    <span class="meta-item">
                        <i class="fas fa-calendar-day"></i> 
                        <?= date('d M Y', strtotime($lastEvent['date_event'])) ?>
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-map-marker-alt"></i> 
                        <?= htmlspecialchars($lastEvent['lieu']) ?>
                    </span>
                </div>
                
                <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="500">
                    <?= htmlspecialchars(mb_strimwidth($lastEvent['desc_event'], 0, 150, "...")) ?>
                </p>
                
                <button class="hero-cta" 
                        data-aos="fade-up" 
                        data-aos-delay="600"
                        onclick="openModal({
                            id_event: <?= $lastEvent['id_event']; ?>,
                            title: '<?= addslashes($lastEvent['nom_event']); ?>',
                            date: '<?= $lastEvent['date_event']; ?>',
                            location: '<?= addslashes($lastEvent['lieu']); ?>',
                            description: '<?= addslashes($lastEvent['desc_event']); ?>',
                            image: '<?= addslashes($webPath); ?>'
                        })">
                    Participer Maintenant <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>
    <?php else: ?>
        <div class="hero-event-image" style="background-color: #4a6bff;"></div>
        <div class="hero-overlay">
            <h1 class="hero-title">Aucun événement à venir</h1>
            <p class="hero-subtitle">Revenez bientôt pour découvrir nos prochains événements</p>
        </div>
    <?php endif; ?>
</section>

<div class="search-sort-container">
    <form method="POST" action="eventsF.php" class="event-filter-form">
        <div class="input-group">
            <i class="fas fa-search"></i>
            <input type="text" name="search" class="search-field" placeholder="Rechercher un événement"
                value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
        </div>
        <div class="input-group">
            <i class="fas fa-sort"></i>
            <select name="sort" id="sort" class="sort-dropdown">
                <option value="asc" <?= isset($_GET['sort']) && $_GET['sort'] == 'asc' ? 'selected' : '' ?>>Trier par date (Ascendant)</option>
                <option value="desc" <?= isset($_GET['sort']) && $_GET['sort'] == 'desc' ? 'selected' : '' ?>>Trier par date (Descendant)</option>
            </select>
        </div>

        <button type="submit" class="submit-btn">
            <i class="fas fa-filter"></i> Filtrer
        </button>
    </form>
</div>


<div class="container">
    <h1>Nos Événements à Venir</h1>
    
    <div class="events-grid">
        <?php if (empty($events)): ?>
            <div class="no-events">
                <p>Aucun événement prévu pour le moment. Revenez plus tard!</p>
            </div>
        <?php else: ?>
            <?php
            // Pagination
            $eventsPerPage = 6;
            $totalEvents = count($events);
            $totalPages = ceil($totalEvents / $eventsPerPage);

            // Current page
            $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

            // Offset
            $offset = ($currentPage - 1) * $eventsPerPage;

            // Paginated events
            $paginatedEvents = array_slice($events, $offset, $eventsPerPage);
            ?>

            <?php foreach ($paginatedEvents as $event): ?>
                <div class="event-card" data-aos="fade-up">
                    <?php
                    $defaultImage = '/validationfinale/View/EVENT/BackOffice/uploads/events/default-event.jpg';
                    $imageFile = !empty($event['img_event']) ? basename($event['img_event']) : 'default-event.jpg';
                    $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/validationfinale/View/EVENT/BackOffice/uploads/events/' . $imageFile;
                    $webPath = '/validationfinale/View/EVENT/BackOffice/uploads/events/' . $imageFile;
                    
                    if (!file_exists($fullPath)) {
                        $webPath = $defaultImage;
                    }
                    ?>

                    <img src="<?= htmlspecialchars($webPath) ?>" alt="<?= htmlspecialchars($event['nom_event']) ?>" class="event-image">
                    <div class="event-content">
                        <h2 class="event-title"><?= htmlentities($event['nom_event'], ENT_QUOTES, 'UTF-8') ?></h2>
                        <div class="event-date"><?= date('d/m/Y', strtotime($event['date_event'])) ?></div>
                        <button class="btn-detail" onclick="openModal({
                            id_event: <?= $event['id_event']; ?>,
                            title: '<?= addslashes($event['nom_event']); ?>',
                            date: '<?= $event['date_event']; ?>',
                            location: '<?= addslashes($event['lieu']); ?>',
                            description: '<?= addslashes($event['desc_event']); ?>',
                            image: '<?= addslashes($event['img_event']); ?>'
                        })">
                            Voir Détails
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <?php if ($totalPages > 1): ?>
    <div class="pagination-container">
        <ul class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="<?= ($i == $currentPage) ? 'active' : '' ?>">
                    <a href="?page=<?= $i ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?><?= !empty($sortOrder) ? '&sort=' . urlencode($sortOrder) : '' ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div id="eventModal" style="display: none;">
    <div id="modalContent">
        <span id="closeModal">&times;</span>
        <img id="modalImage" src="" alt="Event Image">
        <h2 id="modalTitle"></h2>
        <p><strong><i class="fas fa-calendar-day"></i> Date:</strong> <span id="modalDate"></span></p>
        <p><strong><i class="fas fa-map-marker-alt"></i> Lieu:</strong> <span id="modalLocation"></span></p>
        <p><strong><i class="fas fa-info-circle"></i> Description:</strong> <span id="modalDescription"></span></p>
        
        <div style="margin-bottom: 25px; text-align: center;">
            <h2 style="font-size: 1.8rem; color: #007bff; margin-bottom: 10px; display: flex; justify-content: center; align-items: center; gap: 10px;">
                <i class="fas fa-calendar-check"></i> Participation à l'événement
            </h2>
            <p style="font-size: 1rem; color: #dc3545; background: #fff3cd; border: 1px solid #ffeeba; padding: 10px 15px; border-radius: 8px; display: inline-block;">
                <i class="fas fa-exclamation-triangle"></i> Veuillez remplir le formulaire avant de participer à l'événement.
            </p>
        </div>

        <form id="participationForm" onsubmit="return validateParticipationForm(event)">
            <input type="hidden" name="id_event" value="<?= htmlspecialchars($event['id_event']) ?>">
            <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['id_user']) ?>">

            <div style="margin-bottom: 20px;">
                <label for="telnbr" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">📞 Numéro de téléphone</label>
                <input type="text" id="telnbr" name="telnbr" placeholder="Votre numéro de téléphone" 
                       style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;">
            </div>

            <div style="margin-bottom: 25px;">
                <label for="nbrguest" style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">👥 Nombre d'invités</label>
                <input type="number" id="nbrguest" name="nbrguest" placeholder="Nombre d'invités (1-2)"
                       style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem;">
            </div>

            <button type="submit" class="btn-participate" style="background: #ffc107; color: #212529;">
                ✅ Veuillez verifié vos informations
            </button>
        </form>

        <a id="btnParticipate" class="btn-participate">Participer</a>
    </div>
</div>

<script>
function openModal(eventData) {
    document.getElementById("modalTitle").innerText = eventData.title;
    document.getElementById("modalDate").innerText = eventData.date;
    document.getElementById("modalLocation").innerText = eventData.location;
    document.getElementById("modalDescription").innerText = eventData.description;

    const imagePath = eventData.image && eventData.image.trim() !== ''
        ? '/validationfinale/View/BackOffice/' + eventData.image
        : '/validationfinale/View/BackOffice/uploads/events/default-event.jpg';
    document.getElementById("modalImage").src = imagePath;

    document.getElementById('btnParticipate').href = 'participate.php?event_id=' + encodeURIComponent(eventData.id_event);
    document.getElementById("eventModal").style.display = "block";
}

function validateParticipationForm(event) {
    event.preventDefault();
    const phoneNumber = document.getElementById('telnbr').value.trim();
    const numberOfGuests = parseInt(document.getElementById('nbrguest').value.trim(), 10);
    const phoneRegex = /^\d{8}$/;

    if (!phoneRegex.test(phoneNumber)) {
        alert("Veuillez entrer un numéro de téléphone valide (ex : 94452454).");
        return false;
    }

    if (isNaN(numberOfGuests) || numberOfGuests < 1 || numberOfGuests > 2) {
        alert("Veuillez entrer un nombre d'invités entre 1 et 2.");
        return false;
    }

    alert("Maintenant vous pouvez participer, Veuillez cliquer sur le bouton Participer ! Merci.");
}

document.getElementById('closeModal').addEventListener('click', () => {
    document.getElementById('eventModal').style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target.id === 'eventModal') {
        document.getElementById('eventModal').style.display = 'none';
    }
});

document.addEventListener("DOMContentLoaded", function () {
    AOS.init({ duration: 1000 });
});
</script>



    <!-- Notification Floater -->
    <?php
// Make sure these variables are defined before this block
$getUpcomingEventNotifications = (new EventC())->getUpcomingEventNotifications();
$totalNotifications = count($getUpcomingEventNotifications ?? []);

$eventDataJson = json_encode($getUpcomingEventNotifications ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>

<div class="notification-floater" role="region" aria-label="Notifications">
    <button class="notification-btn" id="notificationBtn" aria-label="View notifications">
        <i class="fas fa-bell" aria-hidden="true"></i>
        <?php if (!empty($totalNotifications) && $totalNotifications > 0): ?>
            <span class="notification-badge" aria-label="<?= $totalNotifications ?> new notifications"><?= htmlspecialchars($totalNotifications) ?></span>
        <?php endif; ?>
    </button>

    <!-- Notification Panel -->
    <div class="notification-panel" id="notificationPanel" role="dialog" aria-labelledby="notificationHeader" aria-hidden="true">
        <div class="notification-header">
            <h3 id="notificationHeader">Alertes</h3>
            <button class="close-panel" aria-label="Close notification panel">&times;</button>
        </div>

        <div class="notification-body">
            <?php if (!empty($getUpcomingEventNotifications)): ?>
                <div class="notification-category">
                    <h4><i class="fas fa-calendar-alt warning-icon" aria-hidden="true"></i> Événements à venir</h4>
                    <?php foreach ($getUpcomingEventNotifications as $event): ?>
                        <div class="notification-alert event-alert"
                             data-event-id="<?= htmlspecialchars($event['id_event']) ?>"
                             role="button"
                             aria-label="View event details for <?= htmlspecialchars($event['nom_event']) ?>">
                            <div class="alert-content">
                                <p class="alert-title"><?= htmlspecialchars($event['nom_event']) ?></p>
                                <p class="alert-desc">
                                    Date: <?= date('d/m/Y', strtotime($event['date_event'])) ?> |
                                    Lieu: <?= htmlspecialchars($event['lieu'] ?? 'N/A') ?>
                                </p>
                            </div>
                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-notifications">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                    <p>Aucune alerte pour le moment</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="notification-footer">
            <a href="#" class="mark-all-read" aria-label="Mark all notifications as read">Marquer tout comme lu</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const notificationBtn = document.getElementById('notificationBtn');
    const notificationPanel = document.getElementById('notificationPanel');
    const closeBtn = document.querySelector('.close-panel');
    const markAllReadLink = document.querySelector('.mark-all-read');
    const alerts = document.querySelectorAll('.notification-alert');

    // Embedded PHP JSON data
    const events = <?= $eventDataJson ?>;

    // Show/hide panel
    notificationBtn?.addEventListener('click', function (e) {
        e.stopPropagation();
        const isShown = notificationPanel.classList.toggle('show');
        notificationPanel.setAttribute('aria-hidden', !isShown);
    });

    // Close panel
    closeBtn?.addEventListener('click', function () {
        notificationPanel.classList.remove('show');
        notificationPanel.setAttribute('aria-hidden', 'true');
    });

    // Outside click closes panel
    document.addEventListener('click', function (e) {
        if (!notificationPanel.contains(e.target) && !notificationBtn.contains(e.target)) {
            notificationPanel.classList.remove('show');
            notificationPanel.setAttribute('aria-hidden', 'true');
        }
    });

    // Mark all as read
    markAllReadLink?.addEventListener('click', function (e) {
        e.preventDefault();
        const badge = document.querySelector('.notification-badge');
        if (badge) badge.style.display = 'none';
        notificationPanel.classList.remove('show');
        notificationPanel.setAttribute('aria-hidden', 'true');
    });

    // Handle clicks on notifications
    alerts.forEach((alert, index) => {
        alert.style.animationDelay = `${index * 0.1}s`;
        alert.classList.add('animate-alert');

        alert.addEventListener('click', function () {
            const eventId = parseInt(this.getAttribute('data-event-id'));
            const event = events.find(e => parseInt(e.id_event) === eventId);
            if (!event) {
                console.warn('Event not found for ID:', eventId);
                return;
            }

            const basePath = '/validationfinale/View/EVENT/BackOffice/';
            const imagePath = event.img_event && event.img_event.trim() !== ''
                ? `${basePath}${event.img_event}`
                : `${basePath}uploads/events/default-event.jpg`;
            // Ensure openModal is defined
            if (typeof openModal === 'function') {
                openModal({
                    id_event: event.id_event,
                    title: event.nom_event,
                    date: event.date_event,
                    location: event.lieu || 'N/A',
                    description: event.desc_event,
                    image: imagePath
                });
            } else {
                console.warn('openModal function is not defined.');
            }

            notificationPanel.classList.remove('show');
            notificationPanel.setAttribute('aria-hidden', 'true');
        });
    });
});
</script>


<footer class="footer">
        <div class="container">
            <div class="row">
                <div class="footer-col">
                    <h4>company</h4>
                    <ul>
                        <li><a href="#">about us</a></li>
                        <li><a href="#">our services</a></li>
                        <li><a href="#">privacy policy</a></li>
                        <li><a href="#">affiliate program</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>get help</h4>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">shipping</a></li>
                        <li><a href="#">returns</a></li>
                        <li><a href="#">order status</a></li>
                        <li><a href="#">payment options</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>follow us</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
</footer>
</body>
</html>