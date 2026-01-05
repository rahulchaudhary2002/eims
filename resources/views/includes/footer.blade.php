<footer class="bg-gray-900 text-white">
    <!-- Main Footer Content -->
    <div class="container max-w-7xl mx-auto py-12 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
            <!-- About EIMS -->
            <div>
                <div class="flex items-center mb-4">
                    <span class="text-2xl font-bold text-primary">EIMS</span>
                </div>
                <p class="text-gray-400 text-sm mb-4">
                    Educational Information Management System - Your comprehensive platform for 
                    managing educational institutions, courses, and student information.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                        <x-lucide-facebook class="w-5 h-5" />
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                        <x-lucide-twitter class="w-5 h-5" />
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                        <x-lucide-linkedin class="w-5 h-5" />
                    </a>
                    <a href="#" class="text-gray-400 hover:text-primary transition-colors">
                        <x-lucide-instagram class="w-5 h-5" />
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ url('/') }}" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2">
                            <x-lucide-chevron-right class="w-4 h-4" />
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2">
                            <x-lucide-chevron-right class="w-4 h-4" />
                            Courses
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2">
                            <x-lucide-chevron-right class="w-4 h-4" />
                            Schools
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2">
                            <x-lucide-chevron-right class="w-4 h-4" />
                            Colleges
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Important Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Important Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2">
                            <x-lucide-chevron-right class="w-4 h-4" />
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2">
                            <x-lucide-chevron-right class="w-4 h-4" />
                            Contact
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2">
                            <x-lucide-chevron-right class="w-4 h-4" />
                            Privacy Policy
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-gray-400 hover:text-primary transition-colors flex items-center gap-2">
                            <x-lucide-chevron-right class="w-4 h-4" />
                            Terms of Service
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact Us</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <x-lucide-map-pin class="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                        <span class="text-gray-400 text-sm">
                            123 Education Street, Knowledge City<br>
                            State, Country 12345
                        </span>
                    </li>
                    <li class="flex items-center gap-3">
                        <x-lucide-phone class="w-5 h-5 text-primary" />
                        <a href="tel:+1234567890" class="text-gray-400 hover:text-primary transition-colors text-sm">
                            +1 (234) 567-890
                        </a>
                    </li>
                    <li class="flex items-center gap-3">
                        <x-lucide-mail class="w-5 h-5 text-primary" />
                        <a href="mailto:info@eims.com" class="text-gray-400 hover:text-primary transition-colors text-sm">
                            info@eims.com
                        </a>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Newsletter Subscription -->
        <div class="mt-12 pt-8 border-t border-gray-800">
            <div class="max-w-xl mx-auto text-center">
                <h3 class="text-xl font-semibold mb-2">Stay Updated</h3>
                <p class="text-gray-400 text-sm mb-4">
                    Subscribe to our newsletter for the latest updates on courses and educational news.
                </p>
                <form class="flex gap-2 max-w-md mx-auto">
                    <input
                        type="email"
                        placeholder="Enter your email"
                        class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-colors text-white"
                        required
                    />
                    <button
                        type="submit"
                        class="px-4 py-2 bg-primary text-white rounded-lg font-medium hover:bg-primary/90 transition-colors flex items-center gap-2"
                    >
                        <x-lucide-send class="w-4 h-4" />
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Bottom Bar -->
    <div class="bg-gray-950 py-4">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-500 text-sm text-center md:text-left">
                    &copy; {{ date('Y') }} EIMS. All rights reserved.
                </p>
                <div class="flex items-center gap-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-500 hover:text-primary transition-colors text-sm">
                        Privacy Policy
                    </a>
                    <a href="#" class="text-gray-500 hover:text-primary transition-colors text-sm">
                        Terms of Service
                    </a>
                    <a href="#" class="text-gray-500 hover:text-primary transition-colors text-sm">
                        Cookie Policy
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button 
        onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
        class="fixed bottom-4 right-4 bg-blue-500 text-white p-2 rounded-full shadow-lg hover:bg-blue/90 transition-colors z-40"
        aria-label="Back to top"
    >
        <x-lucide-chevron-up class="w-5 h-5" />
    </button>
</footer>