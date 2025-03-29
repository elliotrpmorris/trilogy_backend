import Image from "next/image";
import Link from "next/link";

export default function Home() {
  return (
    <div className="min-h-screen">
      {/* Top Navigation */}
      <header className="bg-white">
        <div className="container mx-auto px-4 py-2">
          <div className="flex flex-col md:flex-row items-center justify-between">
            <div className="text-center md:text-left mb-4 md:mb-0">
              <p className="italic">Sign up for the Trilogy Health App today for 10% off your first month</p>
            </div>
            <div className="w-full md:w-auto">
              <div className="flex">
                <input
                  type="search"
                  className="form-input w-full rounded-l"
                  placeholder="Search"
                />
                <button className="bg-gray-100 px-4 rounded-r">
                  <i className="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <div className="container mx-auto px-4 py-4">
          <div className="flex flex-col md:flex-row items-center justify-between">
            <div className="mb-4 md:mb-0">
              <Link href="/">
                <Image src="/logo.png" alt="Trilogy Health" width={150} height={50} />
              </Link>
            </div>
            <div className="flex items-center space-x-4">
              <button className="text-gray-600 hover:text-gray-900">Login</button>
              <button className="text-gray-600 hover:text-gray-900">
                <i className="fas fa-user-circle text-xl"></i>
              </button>
              <button className="text-gray-600 hover:text-gray-900 relative">
                <i className="fas fa-shopping-bag text-xl"></i>
                <span className="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                  4
                </span>
              </button>
            </div>
          </div>
        </div>
      </header>

      {/* Hero Section */}
      <section className="bg-gray-50 py-20">
        <div className="container mx-auto px-4">
          <div className="flex flex-col md:flex-row items-center">
            <div className="md:w-1/2 mb-10 md:mb-0">
              <h1 className="text-4xl md:text-5xl font-bold mb-4">
                TRILOGY <span className="text-blue-600">HEALTH</span>
              </h1>
              <h2 className="text-xl md:text-2xl mb-6">
                An expert nutritionist, coach and physiotherapist in your pocket.
              </h2>
              <h3 className="text-lg mb-8">Download the app below to get started</h3>
              <div className="flex space-x-4">
                <Image src="/google.png" alt="Google Play" width={150} height={50} />
                <Image src="/app.png" alt="App Store" width={150} height={50} />
              </div>
            </div>
            <div className="md:w-1/2">
              <Image src="/banner-img.png" alt="Banner" width={500} height={500} />
            </div>
          </div>
        </div>
      </section>

      {/* About Section */}
      <section className="py-20">
        <div className="container mx-auto px-4">
          <div className="max-w-4xl mx-auto text-center">
            <Image src="/img-1.png" alt="About" width={600} height={400} className="mx-auto mb-8" />
            <h2 className="text-3xl font-bold mb-4">WHY TRILOGY HEALTH?</h2>
            <p className="text-lg mb-8">
              With over 50 recipes curated by nutritionists, tailored workout plans and injury prevention programmes, 
              this app will bring together everything you need to achieve and maintain your health and fitness goals.
            </p>
            <button className="bg-blue-600 text-white px-8 py-3 rounded-full hover:bg-blue-700">
              Read More
            </button>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section className="bg-gray-50 py-20">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-center mb-12">
            EVERYTHING YOU NEED<br />IN ONE APP
          </h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div className="text-center">
              <Image src="/img-i1.png" alt="Fitness Coach" width={100} height={100} className="mx-auto mb-4" />
              <h3 className="text-xl font-bold mb-4">Fitness Coach</h3>
              <p>Build strength, muscle tone and mobility with over 100 exercises and more than 50 workouts.</p>
            </div>
            <div className="text-center">
              <Image src="/img-i2.png" alt="Nutritionist" width={100} height={100} className="mx-auto mb-4" />
              <h3 className="text-xl font-bold mb-4">Nutritionist</h3>
              <p>Over 50 simple and effective recipes, that are nutritionally complete and tailored to your goals.</p>
            </div>
            <div className="text-center">
              <Image src="/img-i3.png" alt="Physiotherapist" width={100} height={100} className="mx-auto mb-4" />
              <h3 className="text-xl font-bold mb-4">Physiotherapist</h3>
              <p>Targeted injury prevention programming with over 100 exercises for rehabilitation.</p>
            </div>
          </div>
          <div className="text-center mt-12">
            <button className="bg-blue-600 text-white px-8 py-3 rounded-full hover:bg-blue-700">
              Learn More
            </button>
          </div>
        </div>
      </section>

      {/* Team Section */}
      <section className="py-20">
        <div className="container mx-auto px-4">
          <h2 className="text-3xl font-bold text-center mb-4">MEET THE TRILOGY HEALTH TEAM</h2>
          <p className="text-center mb-12">
            The Trilogy Health experts have collectively helped to transform the lives of 1000's of people.
          </p>
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            <div className="bg-white rounded-lg shadow-lg overflow-hidden">
              <Image src="/img-1.jpg" alt="Luke Stopford" width={400} height={300} />
              <div className="p-6">
                <p className="text-blue-600 mb-1">Fitness Coach</p>
                <h3 className="text-xl font-bold mb-4">LUKE STOPFORD</h3>
                <p>
                  Luke was just 16 when he began coaching and since then, has revolutionised fitness with his UKSCA certified Yorkshire based gym - Yorkshire Strength. 
                  As a British Weightlifting Pathway Coach, Luke has coached everyone from beginners, to some of the UK's best athletes.
                </p>
              </div>
            </div>
            <div className="bg-white rounded-lg shadow-lg overflow-hidden">
              <Image src="/img-2.jpg" alt="Fallon Parker" width={400} height={300} />
              <div className="p-6">
                <p className="text-blue-600 mb-1">Nutritionist</p>
                <h3 className="text-xl font-bold mb-4">FALLON PARKER</h3>
                <p>
                  Fallon is a registered Sports and Exercise nutritionist and has helped 100s of people to transform their lifestyle and fuel their body properly. 
                  Alongside her day job, she is currently completing a Masters of Research in Psychology and Physical Activity.
                </p>
              </div>
            </div>
            <div className="bg-white rounded-lg shadow-lg overflow-hidden">
              <Image src="/img-3.jpg" alt="Dominique" width={400} height={300} />
              <div className="p-6">
                <p className="text-blue-600 mb-1">Physiotherapist</p>
                <h3 className="text-xl font-bold mb-4">DOMINIQUE</h3>
                <p>
                  Dominique has over 10 years experience as a leading physiotherapist, having worked with thousands of individuals for injury prevention and management. 
                  Alongside Trilogy Health, she is the owner and head physiotherapist at Unique Physiotherapy.
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Contact Section */}
      <section className="bg-gray-50 py-20">
        <div className="container mx-auto px-4">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
              <h2 className="text-3xl font-bold mb-4">Get in Touch</h2>
              <p className="mb-4">I'm a paragraph. Click here to add your own text and edit me.</p>
              <p>
                Email: info@mysite.com<br />
                Phone: 123-456-7890
              </p>
            </div>
            <div>
              <form className="space-y-4">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <input type="text" placeholder="First Name" className="form-input w-full" />
                  <input type="text" placeholder="Last Name" className="form-input w-full" />
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <input type="email" placeholder="Email" className="form-input w-full" />
                  <input type="text" placeholder="Subject" className="form-input w-full" />
                </div>
                <textarea placeholder="Type your message here..." className="form-textarea w-full h-32"></textarea>
                <button type="submit" className="bg-blue-600 text-white px-8 py-3 rounded-full hover:bg-blue-700">
                  Submit
                </button>
              </form>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-gray-900 text-white py-8">
        <div className="container mx-auto px-4 text-center">
          <p>© 2024 Trilogy Health</p>
        </div>
      </footer>
    </div>
  );
}
