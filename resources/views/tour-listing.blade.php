<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Tours - {{ $settings->app_name ?? 'Tour Management' }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --dark-color: #2c3e50;
        }

        .navbar-custom {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .page-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 80px 0 40px;
            margin-top: 76px;
        }

        .tour-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s;
            margin-bottom: 30px;
            height: 100%;
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

        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
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
                        <a class="nav-link active" href="{{ route('tours.listing') }}">Tours</a>
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

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1 class="mb-0">Explore Our Tours</h1>
            <p class="lead mb-0">Find your perfect adventure</p>
        </div>
    </div>

    <!-- Tours Section -->
    <section class="py-5">
        <div class="container">
            <!-- Filters -->
            <div class="filter-section">
                <form method="GET" action="{{ route('tours.listing') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Search Tours</label>
                            <input type="text" name="search" class="form-control"
                                   placeholder="Search by name, destination..."
                                   value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="upcoming" {{ $status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="ongoing" {{ $status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="latest" {{ $sortBy == 'latest' ? 'selected' : '' }}>Latest</option>
                                <option value="upcoming" {{ $sortBy == 'upcoming' ? 'selected' : '' }}>Upcoming First</option>
                                <option value="price_low" {{ $sortBy == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ $sortBy == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
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

            <!-- Results Count -->
            <div class="mb-4">
                <h5 class="text-muted">Found {{ $tours->total() }} tour(s)</h5>
            </div>

            <!-- Tours Grid -->
            @if($tours && $tours->count() > 0)
                <div class="row">
                    @foreach($tours as $tour)
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
                                <p class="text-muted small">{{ Str::limit($tour->description, 100) }}</p>
                                @endif

                                <div class="mt-auto">
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
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

                                    <div class="mt-3">
                                        @if($tour->tour_members_count < $tour->max_members && in_array($tour->status, ['upcoming', 'ongoing']))
                                            <a href="#" class="btn btn-primary w-100">
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $tours->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="alert alert-info text-center py-5">
                    <i class="fas fa-info-circle fa-3x mb-3"></i>
                    <h4>No tours found</h4>
                    <p>Try adjusting your search filters or check back later for new tours.</p>
                    <a href="{{ route('tours.listing') }}" class="btn btn-primary">Reset Filters</a>
                </div>
            @endif
        </div>
    </section>

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

