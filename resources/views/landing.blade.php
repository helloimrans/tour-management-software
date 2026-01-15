<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings->app_name ?? 'Tour Management' }} - Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/css/landing-responsive.css') }}">
    <style>
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)),
                        url('{{ asset("frontend/video/tour-bg.jpg") }}') center/cover;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('landing') }}">
                @if($settings->app_logo_url ?? null)
                    <img src="{{ $settings->app_logo_url }}" alt="{{ $settings->app_name ?? 'Logo' }}" style="max-height: 40px; width: auto;">
                @else
                    <i class="fas fa-plane-departure text-primary"></i>
                @endif
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
                        <a class="nav-link" href="{{ route('member.show.register') }}">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
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
                <a href="{{ route('member.show.register') }}" class="btn btn-custom btn-secondary-custom">Join Now</a>
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
    <section class="py-5 featured-tour-section">
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
                    <a href="{{ route('member.show.register') }}" class="btn btn-lg btn-primary">Join This Tour</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- All Tours Section -->
    <section class="py-5" style="background: #f8f9fa;">
        <div class="container">
            <div class="section-title">
                <h2>All Available Tours</h2>
                <p class="text-muted">Explore our exciting tour packages</p>
            </div>

            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" action="{{ route('tours.listing') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Search Tours</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by name, destination..."
                                   value="{{ request('search') ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="upcoming" {{ request('sort') == 'upcoming' ? 'selected' : '' }}>Upcoming First</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            @if(isset($allTours) && $allTours && $allTours->count() > 0)
                <!-- Results Count -->
                <div class="mb-4">
                    <h5 class="text-muted">Found {{ $allTours->count() }} tour(s)</h5>
                </div>

                <!-- Tours Grid -->
                <div class="row">
                    @foreach($allTours as $tour)
                    <div class="col-md-4 mb-4">
                        <div class="card tour-card">
                            <img src="{{ $tour->image_url }}" class="card-img-top" alt="{{ $tour->name }}">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span class="badge bg-{{ $tour->status == 'ongoing' ? 'success' : ($tour->status == 'upcoming' ? 'info' : 'secondary') }}">
                                        {{ ucfirst($tour->status) }}
                                    </span>
                                    @if($tour->tour_members_count >= $tour->max_members)
                                        <span class="badge bg-danger">Full</span>
                                    @elseif($tour->tour_members_count >= ($tour->max_members * 0.8))
                                        <span class="badge bg-warning">Almost Full</span>
                                    @endif
                                </div>

                                <h5 class="card-title">{{ $tour->name }}</h5>

                                <p class="text-muted mb-2">
                                    <i class="fas fa-map-marker-alt text-danger"></i> {{ $tour->destination }}
                                </p>

                                <p class="text-muted mb-2">
                                    <i class="fas fa-calendar"></i>
                                    {{ $tour->start_date->format('d M Y') }} - {{ $tour->end_date->format('d M Y') }}
                                </p>

                                @if($tour->description)
                                <p class="text-muted small mb-3">{{ Str::limit($tour->description, 100) }}</p>
                                @endif

                                <div class="mt-auto">
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <small class="text-muted d-block">Price per person</small>
                                            <span class="text-success fw-bold fs-5">৳{{ number_format($tour->per_member_cost, 2) }}</span>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted d-block">Availability</small>
                                            <span class="fw-bold">
                                                <i class="fas fa-users text-info"></i>
                                                {{ $tour->tour_members_count }}/{{ $tour->max_members }}
                                            </span>
                                        </div>
                                    </div>

                                    <div>
                                        @if($tour->tour_members_count < $tour->max_members && in_array($tour->status, ['upcoming', 'ongoing']))
                                            <a href="{{ route('member.show.register') }}" class="btn btn-primary w-100">
                                                <i class="fas fa-sign-in-alt"></i> Join Now
                                            </a>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                @if($tour->tour_members_count >= $tour->max_members)
                                                    <i class="fas fa-times-circle"></i> Full
                                                @else
                                                    <i class="fas fa-ban"></i> Not Available
                                                @endif
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="text-center mt-4">
                    <a href="{{ route('tours.listing') }}" class="btn btn-lg btn-outline-primary">View All Tours</a>
                </div>
            @else
                <!-- No Tours Found -->
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>No tours found</h4>
                    <p>Try adjusting your search filters or check back later for new tours.</p>
                    <a href="{{ route('tours.listing') }}" class="btn btn-primary mt-3">View All Tours</a>
                </div>
            @endif
        </div>
    </section>

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
                    <div class="mb-2">
                        @if($settings->app_logo_url ?? null)
                            <img src="{{ $settings->app_logo_url }}" alt="{{ $settings->app_name ?? 'Logo' }}" style="max-height: 50px; width: auto;">
                        @else
                            <i class="fas fa-plane-departure" style="font-size: 2rem;"></i>
                        @endif
                    </div>
                    <p>{{ $settings->app_slogan ?? 'Your trusted partner for amazing travel experiences.' }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('landing') }}" class="text-white-50">Home</a></li>
                        <li><a href="{{ route('tours.listing') }}" class="text-white-50">Tours</a></li>
                        <li><a href="{{ route('member.show.register') }}" class="text-white-50">Register</a></li>
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
