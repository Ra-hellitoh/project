import { motion } from "framer-motion";
import { Link } from "react-router-dom";
import { HeroSVG } from "./HeroSVG";

function HeroSection() {
  return (
    <main className="min-h-screen flex flex-col lg:flex-row items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-blue-900 text-white px-4 lg:px-20">
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.8 }}
        className="lg:w-1/2 text-center lg:text-left lg:pr-12"
      >
        <h1 className="text-4xl lg:text-7xl font-bold leading-tight">
          Welcome to Modern
          <span className="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-blue-600">
            {" "}
            Hostel Living
          </span>
        </h1>
        <p className="mt-6 text-xl lg:text-2xl text-gray-300">
          Experience seamless check-ins and premium accommodation designed for your comfort.
        </p>
        <motion.div 
          className="mt-10 space-y-4"
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.5 }}
        >
          <Link
            to="/auth/login"
            className="inline-block px-8 py-3 text-lg font-medium text-white bg-blue-600 rounded-full hover:bg-blue-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-blue-500/50"
          >
            Get Started
          </Link>
          <div className="mt-6">
            <Link
              to="/auth/request"
              className="text-lg text-gray-300 hover:text-blue-400 underline-offset-4 hover:underline transition-colors"
            >
              Request Registration →
            </Link>
          </div>
        </motion.div>
      </motion.div>
      
      <motion.div
        initial={{ opacity: 0, x: 20 }}
        animate={{ opacity: 1, x: 0 }}
        transition={{ duration: 0.8, delay: 0.2 }}
        className="lg:w-1/2 mt-10 lg:mt-0"
      >
        <div className="relative">
          <div className="absolute inset-0 bg-blue-500 blur-xl opacity-20 rounded-full"></div>
          <HeroSVG />
        </div>
      </motion.div>
    </main>
  );
}

export { HeroSection };