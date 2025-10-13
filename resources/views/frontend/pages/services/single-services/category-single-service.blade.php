@extends('frontend.layouts.frontend')
@section('content')
    <!-- breadcrumb area start -->
    <section class="breadcrumb__area breadcrumb__style-5 p-relative include-bg pt-170 pb-110 blue-bg">
        <div class="breadcrumb__bg bg-luminosity include-bg" data-background="assets/img/breadcrumb/breadcrumb-bg-5.jpg">
        </div>
        <div class="container">
            <div class="row align-items-end">
                <div class="col-xxl-7 col-lg-7">
                    <div class="breadcrumb__content breadcrumb__content-2 p-relative z-index-1">
                        <span class="breadcrumb__title-pre">Services</span>
                        <div class="breadcrumb__list">
                            <span><a href="#">Home</a></span>
                            <span class="dvdr"><i class="fa-solid fa-circle-small"></i></span>
                            <span><a href="#">Business</a></span>
                            <span class="dvdr"><i class="fa-solid fa-circle-small"></i></span>
                            <span>Investment Trend Monitor: Top Trends in 2022 </span>
                        </div>
                        <h3 class="breadcrumb__title">Tech Solutions <br> for Business</h3>
                    </div>
                </div>
                <div class="col-xxl-5 col-lg-5">
                    <div class="breadcrumb__content breadcrumb__content-2 p-relative z-index-1">
                        <p>Harry IT allows your business and technology computers to store, transmit, analyze, and
                            manipulate big data.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- breadcrumb area end -->


    <!-- services area start -->
    <section class="services__area pt-95 pb-90">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    @include('frontend.pages.services.single-services.single-service-side-bar')
                </div>
                <div class="col-xl-9 col-lg-8">
                    <div class="services__tab-right">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="responsive" role="tabpanel"
                                aria-labelledby="responsive-tab">
                                <div class="services__item-wrapper-14 pl-100">
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-1.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Responsive Design</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-2.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Speed Optimized</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-3.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Innovative Framework</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-4.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Pixel Perfect</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="speed" role="tabpanel" aria-labelledby="speed-tab">
                                <div class="services__item-wrapper-14 pl-100">
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-2.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Speed Optimized</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-1.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Responsive Design</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-3.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Innovative Framework</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-4.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Pixel Perfect</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="framework" role="tabpanel" aria-labelledby="framework-tab">
                                <div class="services__item-wrapper-14 pl-100">
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-3.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Innovative Framework</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-1.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Responsive Design</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-2.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Speed Optimized</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-4.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Pixel Perfect</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pixel" role="tabpanel" aria-labelledby="pixel-tab">
                                <div class="services__item-wrapper-14 pl-100">
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-4.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Pixel Perfect</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-1.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Responsive Design</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-2.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Speed Optimized</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="services__item-14 mb-30">
                                        <div class="services__thumb-14 transition-3 include-bg"
                                            data-background="assets/img/services/overlay/services-overlay-3.jpg"></div>
                                        <div class="services__content-14">
                                            <h3 class="services__title-14">
                                                <a href="services-details.html">Innovative Framework</a>
                                            </h3>
                                            <p>We are a creative company that focuses on establishing long <br> term
                                                relationships with customers.</p>
                                            <div class="services__btn-14">
                                                <a href="services-details.html" class="tp-link-btn">
                                                    Learn More
                                                    <span>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                        <svg width="26" height="9" viewBox="0 0 26 9"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M21.6934 1L25 4.20003L21.6934 7.4"
                                                                stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M0.999999 4.19897H25" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- services area end -->
@endsection
