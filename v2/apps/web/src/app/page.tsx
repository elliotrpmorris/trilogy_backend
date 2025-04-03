"use client";

import Image from "next/image";
import Link from "next/link";
import { useState, useEffect, useRef } from "react";
import { Dialog, Transition } from "@headlessui/react";
import { Fragment } from "react";
import "@fontsource/inter/400.css";
import "@fontsource/inter/500.css";
import "@fontsource/inter/600.css";
import "@fontsource/inter/700.css";
import "@fontsource/manrope/400.css";
import "@fontsource/manrope/500.css";
import "@fontsource/manrope/600.css";
import "@fontsource/manrope/700.css";

export default function Home() {
  const [isOpen, setIsOpen] = useState(false);
  const [isScrolled, setIsScrolled] = useState(false);
  const [activeSection, setActiveSection] = useState('home');

  const homeRef = useRef<HTMLDivElement>(null);
  const aboutRef = useRef<HTMLDivElement>(null);
  const featuresRef = useRef<HTMLDivElement>(null);
  const teamRef = useRef<HTMLDivElement>(null);
  const contactRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20);
      
      // Update active section based on scroll position
      const sections = [
        { id: 'home', ref: homeRef },
        { id: 'about', ref: aboutRef },
        { id: 'features', ref: featuresRef },
        { id: 'team', ref: teamRef },
        { id: 'contact', ref: contactRef }
      ];

      for (const section of sections) {
        if (section.ref.current) {
          const rect = section.ref.current.getBoundingClientRect();
          if (rect.top <= 100 && rect.bottom >= 100) {
            setActiveSection(section.id);
            break;
          }
        }
      }
    };

    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  const scrollToSection = (sectionId: string) => {
    const sectionRefs = {
      home: homeRef,
      about: aboutRef,
      features: featuresRef,
      team: teamRef,
      contact: contactRef
    };

    const section = sectionRefs[sectionId as keyof typeof sectionRefs];
    if (section?.current) {
      section.current.scrollIntoView({ behavior: 'smooth' });
    }
    setIsOpen(false);
  };

  return (
    <div className="min-h-screen bg-white">
      {/* Navigation */}
      <nav className={`fixed w-full z-50 transition-all duration-300 ${
        isScrolled ? "bg-white shadow-sm" : "bg-transparent"
      }`}>
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex justify-between items-center h-16">
            <Link href="/" className="flex-shrink-0">
              <Image src="/logo.png" alt="Trilogy Health" width={120} height={40} />
            </Link>
            
            {/* Desktop Navigation */}
            <div className="hidden md:flex items-center space-x-8">
              <button 
                onClick={() => scrollToSection('home')}
                className={`font-['Inter'] text-gray-600 hover:text-[#353634] transition-colors ${
                  activeSection === 'home' ? 'text-[#353634] font-medium' : ''
                }`}
              >
                Home
              </button>
              <button 
                onClick={() => scrollToSection('about')}
                className={`font-['Inter'] text-gray-600 hover:text-[#353634] transition-colors ${
                  activeSection === 'about' ? 'text-[#353634] font-medium' : ''
                }`}
              >
                About
              </button>
              <button 
                onClick={() => scrollToSection('features')}
                className={`font-['Inter'] text-gray-600 hover:text-[#353634] transition-colors ${
                  activeSection === 'features' ? 'text-[#353634] font-medium' : ''
                }`}
              >
                Features
              </button>
              <button 
                onClick={() => scrollToSection('team')}
                className={`font-['Inter'] text-gray-600 hover:text-[#353634] transition-colors ${
                  activeSection === 'team' ? 'text-[#353634] font-medium' : ''
                }`}
              >
                Team
              </button>
              <button 
                onClick={() => scrollToSection('contact')}
                className={`font-['Inter'] text-gray-600 hover:text-[#353634] transition-colors ${
                  activeSection === 'contact' ? 'text-[#353634] font-medium' : ''
                }`}
              >
                Contact
              </button>
            </div>

            {/* Mobile menu button */}
            <button
              className="md:hidden"
              onClick={() => setIsOpen(true)}
            >
              <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
              </svg>
            </button>
          </div>
        </div>

        {/* Mobile Navigation */}
        <Transition appear show={isOpen} as={Fragment}>
          <Dialog as="div" className="relative z-50" onClose={() => setIsOpen(false)}>
            <Transition.Child
              as={Fragment}
              enter="ease-out duration-300"
              enterFrom="opacity-0"
              enterTo="opacity-100"
              leave="ease-in duration-200"
              leaveFrom="opacity-100"
              leaveTo="opacity-0"
            >
              <div className="fixed inset-0 bg-black bg-opacity-25" />
            </Transition.Child>

            <div className="fixed inset-0 overflow-y-auto">
              <div className="flex min-h-full items-center justify-center p-4 text-center">
                <Transition.Child
                  as={Fragment}
                  enter="ease-out duration-300"
                  enterFrom="opacity-0 scale-95"
                  enterTo="opacity-100 scale-100"
                  leave="ease-in duration-200"
                  leaveFrom="opacity-100 scale-100"
                  leaveTo="opacity-0 scale-95"
                >
                  <Dialog.Panel className="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                    <div className="flex flex-col space-y-4">
                      <button 
                        onClick={() => scrollToSection('home')}
                        className={`text-left text-gray-600 hover:text-gray-900 transition-colors ${
                          activeSection === 'home' ? 'text-blue-600 font-medium' : ''
                        }`}
                      >
                        Home
                      </button>
                      <button 
                        onClick={() => scrollToSection('about')}
                        className={`text-left text-gray-600 hover:text-gray-900 transition-colors ${
                          activeSection === 'about' ? 'text-blue-600 font-medium' : ''
                        }`}
                      >
                        About
                      </button>
                      <button 
                        onClick={() => scrollToSection('features')}
                        className={`text-left text-gray-600 hover:text-gray-900 transition-colors ${
                          activeSection === 'features' ? 'text-blue-600 font-medium' : ''
                        }`}
                      >
                        Features
                      </button>
                      <button 
                        onClick={() => scrollToSection('team')}
                        className={`text-left text-gray-600 hover:text-gray-900 transition-colors ${
                          activeSection === 'team' ? 'text-blue-600 font-medium' : ''
                        }`}
                      >
                        Team
                      </button>
                      <button 
                        onClick={() => scrollToSection('contact')}
                        className={`text-left text-gray-600 hover:text-gray-900 transition-colors ${
                          activeSection === 'contact' ? 'text-blue-600 font-medium' : ''
                        }`}
                      >
                        Contact
                      </button>
                    </div>
                    <button
                      className="absolute top-4 right-4"
                      onClick={() => setIsOpen(false)}
                    >
                      <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </Dialog.Panel>
                </Transition.Child>
              </div>
            </div>
          </Dialog>
        </Transition>
      </nav>

      {/* Hero Section */}
      <section ref={homeRef} className="pt-32 pb-16 px-4 sm:px-6 lg:px-8 font-['Inter']">
        <div className="max-w-7xl mx-auto">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
              <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-[#353634] mb-6">
                Your Health Journey Starts Here
              </h1>
              <p className="text-xl text-gray-600 mb-8">
                Expert nutrition, coaching, and physiotherapy guidance all in one app.
              </p>
              <div className="flex space-x-4">
                <Image src="/google.png" alt="Google Play" width={150} height={50} />
                <Image src="/app.png" alt="App Store" width={150} height={50} />
              </div>
            </div>
            <div className="relative">
              <Image src="/banner-img.png" alt="Banner" width={600} height={600} className="rounded-2xl shadow-xl" />
            </div>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section ref={aboutRef} className="py-16 bg-[#f8f8f8] font-['Inter']">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center text-[#353634] mb-4">About Us</h2>
          <p className="text-center text-gray-600 mb-12 max-w-3xl mx-auto">
            Our platform offers everyone easy and direct access to a team of expert health professionals, including a Sports and Exercise nutritionist, an experienced physiotherapist, and an exercise coach. With our user-friendly app, you can conveniently connect with these specialists, all from the comfort of your phone. Whether you want personalised nutrition advice, need help managing injury, or want a customised training programme, our team is here to support you.
          </p>
          <p className="text-center text-gray-600 mb-12 max-w-3xl mx-auto">
            Our service is designed to make health and wellness accessible to everyone, offering professional guidance that fits seamlessly into your daily routine. With expert support just a tap away, you can take control of your physical and mental well-being, ensuring you stay on track with your goals at your own pace. Whether at home, work, or on the go, our platform makes it easier to prioritise your health anytime, anywhere.
          </p>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div className="bg-white p-6 rounded-xl shadow-sm border border-[#a2c7ac]">
              <h3 className="text-xl font-bold text-[#353634] mb-4">Our Mission</h3>
              <p className="text-gray-600">
                To provide accessible, professional health and fitness guidance that helps people transform their lives through proper nutrition, effective training, and injury prevention.
              </p>
            </div>
            <div className="bg-white p-6 rounded-xl shadow-sm border border-[#a2c7ac]">
              <h3 className="text-xl font-bold text-[#353634] mb-4">Our Vision</h3>
              <p className="text-gray-600">
                To become the leading platform for comprehensive health and fitness guidance, making professional expertise accessible to everyone, everywhere.
              </p>
            </div>
          </div>
        </div>
      </section>

      {/* Why Trilogy Health Section */}
      <section className="py-16 font-['Inter']">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center text-[#353634] mb-4">Why Trilogy Health?</h2>
          <p className="text-center text-gray-600 mb-12 max-w-3xl mx-auto">
            Experience a new level of convenience and personalised care with our platform, which connects you with our health professionals. You can access expert guidance from your phone, no matter where you are. Whether seeking advice on meal planning, recovering from an injury, or working towards fitness and strength goals, our team is ready to provide tailored support for your lifestyle. You can prioritise your health and wellness in a way that works for you, empowering you to live a healthier, more active life.
          </p>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="bg-white p-6 rounded-xl shadow-sm border border-[#a2c7ac]">
              <Image src="/img-i1.png" alt="Fitness Coach" width={80} height={80} className="mb-4" />
              <h3 className="text-xl font-bold text-[#353634] mb-2">Personalized Training</h3>
              <p className="text-gray-600">Custom workout plans to match your unique goals and fitness level</p>
            </div>
            <div className="bg-white p-6 rounded-xl shadow-sm border border-[#a2c7ac]">
              <Image src="/img-i2.png" alt="Nutritionist" width={80} height={80} className="mb-4" />
              <h3 className="text-xl font-bold text-[#353634] mb-2">Expert Nutrition</h3>
              <p className="text-gray-600">Evidence-based nutrition advice tailored to your body and lifestyle</p>
            </div>
            <div className="bg-white p-6 rounded-xl shadow-sm border border-[#a2c7ac]">
              <Image src="/img-i3.png" alt="Physiotherapist" width={80} height={80} className="mb-4" />
              <h3 className="text-xl font-bold text-[#353634] mb-2">Injury Prevention</h3>
              <p className="text-gray-600">Professional guidance to recover from and prevent injuries</p>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section ref={featuresRef} className="py-16 bg-[#f8f8f8] font-['Inter']">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center text-[#353634] mb-12">Everything You Need</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                image: "/img-i1.png",
                title: "Fitness Coach",
                description: "Build strength and mobility with expert-guided workouts."
              },
              {
                image: "/img-i2.png",
                title: "Nutritionist",
                description: "Simple, effective recipes tailored to your goals."
              },
              {
                image: "/img-i3.png",
                title: "Physiotherapist",
                description: "Prevent injuries and rehabilitate with professional guidance."
              }
            ].map((feature) => (
              <div key={feature.title} className="bg-white p-6 rounded-xl shadow-sm border border-[#a2c7ac]">
                <Image src={feature.image} alt={feature.title} width={80} height={80} className="mb-4" />
                <h3 className="text-xl font-bold text-[#353634] mb-2">{feature.title}</h3>
                <p className="text-gray-600">{feature.description}</p>
              </div>
            ))}
          </div>
        </div>
      </section>


      {/* Team Section */}
      <section ref={teamRef} className="py-16 bg-[#f8f8f8] font-['Inter']">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-3xl font-bold text-center text-[#353634] mb-4">Meet Our Team</h2>
          <p className="text-center text-gray-600 mb-12 max-w-2xl mx-auto">
            Our experts have helped transform thousands of lives through personalized health and fitness guidance.
          </p>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                image: "/Luke.JPG",
                role: "Fitness Coach",
                name: "Luke Stopford",
                description: "Luke is a UKSCA-certified British Weightlifting Pathway coach with strength training and athletic development expertise. As the owner of Yorkshire Strength, he provides personalised coaching to help athletes of all levels improve performance, build strength, and reach their full potential."
              },
              {
                image: "/Fallon.JPG",
                role: "Nutritionist",
                name: "Fallon Parker",
                description: "Fallon, a dedicated Registered Sports and Exercise Nutritionist, has helped over 100 clients reshape their approach to food and nutrition. She promotes sustainable habits that enhance performance and well-being and offers practical, evidence-based guidance. Whether you're an athlete or seeking personalised support, Fallon makes nutrition enjoyable and effective for your goals."
              },
              {
                image: "/Dom.JPG",
                role: "Physiotherapist",
                name: "Dominique",
                description: "Dom, the owner of Unique Physiotherapy, has over 10 years of experience in injury prevention and management. Focusing on personalised care, she helps clients recover from injuries, improve mobility, and prevent future issues, guiding them toward better health and strength."
              }
            ].map((member) => (
              <div key={member.name} className="bg-white rounded-xl shadow-sm overflow-hidden border border-[#a2c7ac] flex flex-col h-full">
                <div className="aspect-[4/3] w-full relative">
                  <Image 
                    src={member.image} 
                    alt={member.name} 
                    fill
                    sizes="(max-width: 768px) 100vw, 33vw"
                    className="object-cover object-center"
                    priority
                  />
                </div>
                <div className="p-6 flex-grow">
                  <p className="text-[#a2c7ac] text-sm font-medium mb-1">{member.role}</p>
                  <h3 className="text-xl font-bold text-[#353634] mb-2">{member.name}</h3>
                  <p className="text-gray-600 text-sm">{member.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Contact Section */}
      <section ref={contactRef} className="py-16 font-['Inter']">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="max-w-3xl mx-auto text-center">
            <h2 className="text-3xl font-bold text-[#353634] mb-4">Get Started Today</h2>
            <p className="text-xl text-gray-600 mb-8">
              Download the app and begin your journey to better health.
            </p>
            <div className="flex justify-center space-x-4">
              <Image src="/google.png" alt="Google Play" width={150} height={50} />
              <Image src="/app.png" alt="App Store" width={150} height={50} />
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-[#353634] text-white font-['Inter']">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div className="col-span-1 md:col-span-2">
              <Image src="/logo.png" alt="Trilogy Health" width={120} height={40} className="mb-4" />
              <p className="text-gray-300 mb-4">
                Your comprehensive health and fitness companion, combining expert nutrition, coaching, and physiotherapy guidance.
              </p>
              <div className="flex space-x-4">
                <a href="#" className="text-gray-300 hover:text-white">
                  <span className="sr-only">Facebook</span>
                  <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
                  </svg>
                </a>
                <a href="#" className="text-gray-300 hover:text-white">
                  <span className="sr-only">Instagram</span>
                  <svg className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.315 2c2.43 0 2.784.013 3.808.09 1.064.077 1.791.232 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.233.636.388 1.363.465 2.427.048 1.067.09 1.407.09 4.123v.08c0 2.643-.012 2.987-.09 4.043-.077 1.064-.232 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.233-1.363.388-2.427.465-1.067.048-1.407.09-4.123.09h-.08c-2.643 0-2.987-.012-4.043-.09-1.064-.077-1.791-.232-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.233-.636-.388-1.363-.465-2.427-.047-1.024-.09-1.379-.09-3.808v-.63c0-2.43.013-2.784.09-3.808.077-1.064.232-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.233 1.363-.388 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" />
                  </svg>
                </a>
              </div>
            </div>
            <div>
              <h3 className="text-sm font-semibold text-white tracking-wider uppercase mb-4">Quick Links</h3>
              <ul className="space-y-4">
                <li>
                  <a href="#" className="text-gray-300 hover:text-white">About Us</a>
                </li>
                <li>
                  <a href="#" className="text-gray-300 hover:text-white">Features</a>
                </li>
                <li>
                  <a href="#" className="text-gray-300 hover:text-white">Team</a>
                </li>
                <li>
                  <a href="#" className="text-gray-300 hover:text-white">Contact</a>
                </li>
              </ul>
            </div>
            <div>
              <h3 className="text-sm font-semibold text-white tracking-wider uppercase mb-4">Newsletter</h3>
              <p className="text-gray-300 mb-4">Stay updated with our latest news and updates.</p>
              <form className="space-y-4">
                <div>
                  <label htmlFor="email" className="sr-only">Email address</label>
                  <input
                    id="email"
                    name="email"
                    type="email"
                    autoComplete="email"
                    required
                    className="w-full px-4 py-2 rounded-lg bg-gray-800 border border-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#a2c7ac] focus:border-transparent"
                    placeholder="Enter your email"
                  />
                </div>
                <button
                  type="submit"
                  className="w-full bg-[#a2c7ac] text-[#353634] font-medium px-4 py-2 rounded-lg hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-[#a2c7ac] focus:ring-offset-2 focus:ring-offset-gray-900"
                >
                  Subscribe
                </button>
              </form>
            </div>
          </div>
          <div className="mt-8 pt-8 border-t border-gray-800 text-center">
            <p className="text-gray-300">© 2025 Trilogy Health. All rights reserved.</p>
          </div>
        </div>
      </footer>
    </div>
  );
}
