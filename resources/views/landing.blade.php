<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->app_name ?? 'Tour Management' }} - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('{{ asset("frontend/video/tour-bg.jpg") }}') center/cover;
            height: 600px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }

        .btn-custom {
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-primary-custom {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary-custom:hover {
            background: #2980b9;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .btn-secondary-custom {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary-custom:hover {
            background: white;
            color: var(--dark-color);
        }

        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-title h2 {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--dark-color);
        }

        .tour-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            margin-bottom: 30px;
        }

        .tour-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .tour-card img {
            height: 250px;
            object-fit: cover;
        }

        .tour-card .card-body {
            padding: 25px;
        }

        .tour-card .badge {
            font-size: 0.85rem;
            padding: 8px 15px;
        }

        .feature-box {
            text-align: center;
            padding: 40px 20px;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .feature-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .feature-box i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .footer {
            background: var(--dark-color);
            color: white;
            padding: 40px 0 20px;
            margin-top: 80px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('landing') }}">
                <i class="fas fa-plane-departure text-primary"></i> {{ $settings->app_name ?? 'Tour Management' }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('landing') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tours.listing') }}">Tours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary ms-2" href="#">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary ms-2" href="{{ route('login') }}">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="margin-top: 76px;">
        <div class="hero-content">
            <h1>Explore the World with Us</h1>
            <p>Join amazing tours and create unforgettable memories</p>
            <div class="mt-4">
                <a href="{{ route('tours.listing') }}" class="btn btn-custom btn-primary-custom me-3">Browse Tours</a>
                <a href="#" class="btn btn-custom btn-secondary-custom">Join Now</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="section-title">
                <h2>Why Choose Us?</h2>
                <p class="text-muted">Best tour management experience</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <i class="fas fa-map-marked-alt"></i>
                        <h4>Amazing Destinations</h4>
                        <p class="text-muted">Explore beautiful and exotic locations</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <i class="fas fa-users"></i>
                        <h4>Expert Guides</h4>
                        <p class="text-muted">Professional and friendly tour guides</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <i class="fas fa-shield-alt"></i>
                        <h4>Safe & Secure</h4>
                        <p class="text-muted">Your safety is our top priority</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Tour Section -->
    @if($featuredTour)
    <section class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Featured Tour</h2>
                <p class="text-muted">Don't miss this amazing opportunity</p>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ $featuredTour->image_url }}" alt="{{ $featuredTour->name }}" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <span class="badge bg-{{ $featuredTour->status == 'ongoing' ? 'success' : 'info' }} mb-3">{{ ucfirst($featuredTour->status) }}</span>
                    <h2>{{ $featuredTour->name }}</h2>
                    <p class="lead"><i class="fas fa-map-marker-alt text-danger"></i> {{ $featuredTour->destination }}</p>
                    <p>{{ $featuredTour->description }}</p>
                    <div class="mb-3">
                        <i class="fas fa-calendar text-primary"></i>
                        <strong>{{ $featuredTour->start_date->format('d M Y') }} - {{ $featuredTour->end_date->format('d M Y') }}</strong>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-money-bill-wave text-success"></i>
                        <strong class="text-success fs-4">৳{{ number_format($featuredTour->per_member_cost, 2) }}</strong> / person
                    </div>
                    <div class="mb-4">
                        <i class="fas fa-users text-info"></i>
                        <strong>{{ $featuredTour->tour_members_count }} / {{ $featuredTour->max_members }}</strong> members joined
                    </div>
                    <a href="#" class="btn btn-lg btn-primary">Join This Tour</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Upcoming Tours Section -->
    @if($upcomingTours && $upcomingTours->count() > 0)
    <section class="py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="section-title">
                <h2>Upcoming Tours</h2>
                <p class="text-muted">Explore our exciting tour packages</p>
            </div>
            <div class="row">
                @foreach($upcomingTours as $tour)
                <div class="col-md-4">
                    <div class="card tour-card">
                        <img src="{{ $tour->image_url }}" class="card-img-top" alt="{{ $tour->name }}">
                        <div class="card-body">
                            <span class="badge bg-{{ $tour->status == 'ongoing' ? 'success' : 'info' }} mb-2">{{ ucfirst($tour->status) }}</span>
                            <h5 class="card-title">{{ $tour->name }}</h5>
                            <p class="text-muted mb-2">
                                <i class="fas fa-map-marker-alt text-danger"></i> {{ $tour->destination }}
                            </p>
                            <p class="text-muted mb-3">
                                <i class="fas fa-calendar"></i> {{ $tour->start_date->format('d M Y') }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-success fw-bold fs-5">৳{{ number_format($tour->per_member_cost, 2) }}</span>
                                <span class="text-muted"><i class="fas fa-users"></i> {{ $tour->tour_members_count }}/{{ $tour->max_members }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('tours.listing') }}" class="btn btn-lg btn-outline-primary">View All Tours</a>
            </div>
        </div>
    </section>
    @endif

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-plane-departure"></i> {{ $settings->app_name ?? 'Tour Management' }}</h5>
                    <p>{{ $settings->app_slogan ?? 'Your trusted partner for amazing travel experiences.' }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('landing') }}" class="text-white-50">Home</a></li>
                        <li><a href="{{ route('tours.listing') }}" class="text-white-50">Tours</a></li>
                        <li><a href="#" class="text-white-50">Register</a></li>
                        <li><a href="{{ route('login') }}" class="text-white-50">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p><i class="fas fa-envelope"></i> info@tourmanagement.com</p>
                    <p><i class="fas fa-phone"></i> 01755430927</p>
                </div>
            </div>
            <hr class="border-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ $settings->app_name ?? 'Tour Management' }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
