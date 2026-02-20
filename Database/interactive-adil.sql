-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 20, 2026 at 10:26 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `interactive-adil`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `business_resources`
--

CREATE TABLE `business_resources` (
  `resource_id` int(11) NOT NULL,
  `agent_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `summary` text DEFAULT NULL,
  `content` text DEFAULT NULL,
  `type` enum('business_idea','template','framework','market_research','startup_plan','funding_guide','legal_document','opportunity','case_study','tutorial','event') NOT NULL,
  `description` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_resources`
--

INSERT INTO `business_resources` (`resource_id`, `agent_id`, `category_id`, `title`, `summary`, `content`, `type`, `description`, `file_path`, `created_at`) VALUES
(37, 1, 1, 'Dropshipping Modest Fashion Store on Daraz 2026 Guide', 'Step-by-step to start dropshipping abayas & hijabs with zero inventory in Pakistan.', 'Detailed 12-step process including supplier contacts in Faisalabad/Sialkot, Daraz seller setup, Facebook ads targeting ...', 'business_idea', 'Low investment e-commerce idea targeting women’s modest wear niche.', NULL, '2026-02-20 07:11:03'),
(38, 1, 1, 'E-commerce Business Plan Template (Pakistan Taxes & Logistics)', 'Ready-to-fill business plan including FBR taxes, TCS logistics & Shopify vs Daraz comparison.', 'Business model canvas + financial projections for first 12 months ...', 'startup_plan', 'Editable .docx / Google Doc template tailored for Pakistani e-commerce startups.', 'uploads/templates/ecommerce-plan-pk.docx', '2026-02-20 07:11:03'),
(39, 1, 2, 'Cloud Kitchen Biryani & Desi BBQ Startup in Rawalpindi', 'How to launch a home-based cloud kitchen focusing on premium biryani & BBQ via Foodpanda.', 'Kitchen setup under 5 marla, menu engineering, packaging costs, delivery partner negotiation ...', 'business_idea', 'High-demand food delivery business with 40–60% margins possible.', NULL, '2026-02-20 07:11:03'),
(40, 1, 2, 'Healthy Meal Prep Subscription – Market Research 2026', 'Demand analysis, competitor study (Cheetay, Foodpanda healthy section) & customer survey results.', 'Pakistan urban healthy eating trend report – 28% growth expected in 2025–2026 ...', 'market_research', 'PDF report with graphs showing opportunities in Islamabad/Rawalpindi/Lahore.', 'uploads/research/healthy-meals-pk-2026.pdf', '2026-02-20 07:11:03'),
(41, 1, 3, 'Online Academy for O/A Level Math & Physics in Pakistan', 'Build and monetize Zoom-based classes + recorded courses on Thinkific / Teachable.', 'Pricing strategy, student acquisition via WhatsApp groups & FB ads ...', 'business_idea', 'Proven model – many teachers earning 2–8 lakh/month.', NULL, '2026-02-20 07:11:03'),
(42, 1, 3, 'Course Creation & LMS Setup Template', 'Step-by-step guide + Notion/Moodle/Thinkific checklist for launching your first online course.', 'Content outline, video scripting, pricing tiers, certificate template ...', 'template', 'All-in-one starter kit for Pakistani educators going digital.', NULL, '2026-02-20 07:11:03'),
(43, 1, 4, 'Home-Based Yoga & Fitness Coaching Business 2026', 'Start online/offline yoga classes + sell digital workout plans.', 'Certification options in Pakistan, Instagram Reels strategy, pricing PKR 3k–8k/month ...', 'business_idea', 'Low-cost high-margin wellness service booming post-COVID.', NULL, '2026-02-20 07:11:03'),
(44, 1, 4, 'Organic Skincare Products – Sourcing & Branding Guide', 'How to private label organic face masks/serums from local suppliers and sell online.', 'Supplier list (Karachi/Lahore), halal certification process, packaging ideas ...', 'startup_plan', 'Practical roadmap for beauty entrepreneurs in Pakistan.', NULL, '2026-02-20 07:11:03'),
(45, 1, 5, 'Freelance Digital Marketing Agency Setup in Pakistan', 'From solo freelancer to 5-person agency – Upwork + local clients strategy.', 'Service packages, proposal templates, tools (Canva, SEMrush free alternatives) ...', 'tutorial', 'Detailed 90-day plan used by many Rawalpindi/Islamabad freelancers.', NULL, '2026-02-20 07:11:03'),
(46, 1, 5, 'AI Tools for Pakistani Freelancers – Opportunities Report', 'How ChatGPT, Midjourney & Canva AI can 3x productivity in 2026.', 'Use-case examples, pricing services, client acquisition tips ...', 'opportunity', 'Emerging high-income niche for IT-skilled agents.', NULL, '2026-02-20 07:11:03'),
(47, 1, 6, 'Sustainable Pakistani Clothing Brand Startup Idea', 'Launch ethnic wear brand using local handloom fabrics with online + pop-up sales.', 'Sourcing from Multan/Bahawalpur, branding story, Instagram growth hacks ...', 'business_idea', 'Growing demand for ethical & desi-modern fashion.', NULL, '2026-02-20 07:11:03'),
(48, 1, 6, 'Fashion E-commerce Inventory & Pricing Template', 'Excel sheet for managing stock, profit margins & seasonal discounts.', 'Automated calculations for PKR pricing, shipping, taxes ...', 'template', 'Used by many small Daraz/Instagram sellers.', 'uploads/templates/fashion-inventory.xlsx', '2026-02-20 07:11:03'),
(49, 1, 7, 'Mushroom Farming at Home – Low Cost High Profit 2026', 'Oyster & button mushroom cultivation guide for small scale in Punjab.', 'Setup cost ~80k–1.5 lakh, yield & sales channels (hotels, supermarkets) ...', 'business_idea', 'One of the fastest returning agri-businesses right now.', NULL, '2026-02-20 07:11:03'),
(50, 1, 7, 'Organic Vegetable Supply Chain Opportunity Report', 'Farm-to-home delivery model analysis for Rawalpindi/Islamabad.', 'Market size, competition, subscription model suggestion ...', 'market_research', 'Data-driven idea for peri-urban agriculture entrepreneurs.', NULL, '2026-02-20 07:11:03'),
(51, 1, 8, 'Property Dealing & Rental Management Startup Plan', 'Low-capital real estate agency focusing on DHA/BAHRIA rentals.', 'Commission structure, WhatsApp CRM, legal agreements ...', 'startup_plan', 'Practical for agents starting in twin cities.', NULL, '2026-02-20 07:11:03'),
(52, 1, 9, 'Bridal Makeup & Skincare Services Business Guide', 'How to build a premium bridal & party makeup brand in Pakistan.', 'Portfolio building, pricing, home salon vs studio ...', 'tutorial', 'High-demand seasonal business with good margins.', NULL, '2026-02-20 07:11:03'),
(53, 1, 10, 'Business Consulting for Small Retail Shops in Pakistan', 'Offer inventory, pricing & digital marketing consulting to local shops.', 'Service packages, client acquisition via chambers & markets ...', 'business_idea', 'Untapped B2B consulting opportunity.', NULL, '2026-02-20 07:11:03'),
(54, 1, 10, 'Freelancer Proposal & Contract Templates Pack', 'Upwork/Fiverr winning proposal + simple service agreement in English/Urdu.', 'Customizable Google Docs templates ...', 'template', 'Save 10+ hours per client pitch.', 'uploads/1771574115_UBL-20-Feb-2026 12_20_35.pdf', '2026-02-20 07:11:03');

-- --------------------------------------------------------

--
-- Table structure for table `contents`
--

CREATE TABLE `contents` (
  `content_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `resource_type` enum('tutorial','event') NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contents`
--

INSERT INTO `contents` (`content_id`, `title`, `description`, `resource_type`, `file_path`, `created_at`) VALUES
(1, 'dfsa', 'fdsa', 'tutorial', '1771578822_UBL-20-Feb-2026 12_20_35.pdf', '2026-02-20 09:11:48'),
(2, 'dfsa', 'fdsa', 'tutorial', '1771578806_UBL-20-Feb-2026 12_20_35.pdf', '2026-02-20 09:13:26');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `to_agent_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comments` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `from_user_id`, `to_agent_id`, `rating`, `comments`, `submitted_at`) VALUES
(1, 2, 1, 2, 'fdsafdsa', '2026-02-20 07:21:29'),
(3, NULL, 1, 2, 'fdds', '2026-02-20 09:03:33');

-- --------------------------------------------------------

--
-- Table structure for table `forum_comments`
--

CREATE TABLE `forum_comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_comments`
--

INSERT INTO `forum_comments` (`comment_id`, `post_id`, `user_id`, `comment`, `created_at`) VALUES
(1, 1, 1, 'safdsa', '2026-02-20 07:28:52');

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forum_posts`
--

INSERT INTO `forum_posts` (`post_id`, `user_id`, `title`, `content`, `created_at`) VALUES
(1, 2, 'fdsa', 'fsasdf', '2026-02-20 07:26:47');

-- --------------------------------------------------------

--
-- Table structure for table `private_messages`
--

CREATE TABLE `private_messages` (
  `message_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `private_messages`
--

INSERT INTO `private_messages` (`message_id`, `sender_id`, `receiver_id`, `message`, `is_read`, `sent_at`) VALUES
(1, 2, 1, 'Hy', 1, '2026-02-20 07:37:39'),
(2, 1, 2, 'Yes', 1, '2026-02-20 07:42:47');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'E-Commerce & Online Retail', 'Online stores, dropshipping, marketplaces, digital products, print-on-demand businesses', '2026-02-20 06:57:53'),
(2, 'Food & Beverage', 'Restaurants, cafes, cloud kitchens, food trucks, homemade food delivery, bakeries, healthy meal prep', '2026-02-20 06:57:53'),
(3, 'Education & EdTech', 'Online courses, tutoring, skill development platforms, coaching centers, language learning, test preparation', '2026-02-20 06:57:53'),
(4, 'Health & Wellness', 'Fitness centers, yoga studios, nutrition consulting, mental health services, telemedicine, organic products', '2026-02-20 06:57:53'),
(5, 'Digital Services & IT', 'Web development, mobile app development, UI/UX design, SEO, digital marketing, graphic design, video editing', '2026-02-20 06:57:53'),
(6, 'Fashion & Apparel', 'Clothing brands, boutique stores, custom tailoring, sustainable fashion, accessories, footwear', '2026-02-20 06:57:53'),
(7, 'Agriculture & AgriTech', 'Organic farming, hydroponics, vertical farming, livestock, dairy, poultry, agricultural inputs, farm-to-table', '2026-02-20 06:57:53'),
(8, 'Real Estate & Construction', 'Property dealing, real estate consultancy, interior design, home renovation, construction services', '2026-02-20 06:57:53'),
(9, 'Beauty & Personal Care', 'Salons, spas, makeup artists, skincare brands, cosmetics, haircare products, grooming services', '2026-02-20 06:57:53'),
(10, 'Freelancing & Consulting', 'Business consulting, financial advisory, HR consulting, career coaching, content writing, virtual assistance', '2026-02-20 06:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `cnic` varchar(15) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` enum('user','agent') NOT NULL DEFAULT 'user',
  `expertise_area` varchar(255) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `linkedin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `password`, `dob`, `cnic`, `contact_number`, `address`, `is_active`, `created_at`, `updated_at`, `role`, `expertise_area`, `experience_years`, `bio`, `website`, `linkedin`) VALUES
(1, 'Ahmed Raza Khan', 'ahmedrazakhan', 'ahmed.raza@example.com', '$2y$10$akXhMAEGeHxH1YTt3NLN7ePHK6j08je3A.1LlP4Wc1wCv/t09JpDa', '1990-05-15', '37405-1234567-8', '+923001234567', 'House # 45, Street 12, Sector F-10/2, Islamabad', 1, '2025-08-10 09:30:00', '2026-02-20 07:13:06', 'agent', 'E-Commerce, Digital Marketing, Freelancing, Business Consulting', 8, 'Serial entrepreneur and digital marketing specialist with 8+ years of experience helping 50+ startups in Pakistan launch and scale online businesses. Specializing in Daraz/Shopify stores, social media growth, and freelancing agency setup. Passionate about empowering youth in Rawalpindi and Islamabad.', 'https://ahmedraza.pk', 'https://www.linkedin.com/in/ahmedrazakhan-pk'),
(2, 'Haider Ali', 'Haider1', 'haider@gmail.com', '$2y$10$btvqWom6R2Fwt6il4Xl4NOUWJZDAI1AQLYauDJuqj/jhcQahc.TbC', '2026-02-20', '3243', '0923982', 'P/o Basti Chhina Tehsil jampur District Rajanpur\r\nP/o Basti Chhina Tehsil jampur District Rajanpur', 1, '2026-02-20 07:13:56', '2026-02-20 07:13:56', 'user', '', 0, '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_resources`
--
ALTER TABLE `business_resources`
  ADD PRIMARY KEY (`resource_id`),
  ADD KEY `agent_id` (`agent_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `contents`
--
ALTER TABLE `contents`
  ADD PRIMARY KEY (`content_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `from_user_id` (`from_user_id`),
  ADD KEY `to_agent_id` (`to_agent_id`);

--
-- Indexes for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `private_messages`
--
ALTER TABLE `private_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `business_resources`
--
ALTER TABLE `business_resources`
  MODIFY `resource_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `contents`
--
ALTER TABLE `contents`
  MODIFY `content_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `forum_comments`
--
ALTER TABLE `forum_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `private_messages`
--
ALTER TABLE `private_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `business_resources`
--
ALTER TABLE `business_resources`
  ADD CONSTRAINT `business_resources_ibfk_1` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `business_resources_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`to_agent_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `forum_comments`
--
ALTER TABLE `forum_comments`
  ADD CONSTRAINT `forum_comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `forum_posts` (`post_id`),
  ADD CONSTRAINT `forum_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD CONSTRAINT `forum_posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `private_messages`
--
ALTER TABLE `private_messages`
  ADD CONSTRAINT `private_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `private_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
