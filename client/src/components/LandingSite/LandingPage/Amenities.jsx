import { motion } from "framer-motion";
import { Wifi, Shield, Coffee, Users, MapPin, Clock } from 'lucide-react';

function Amenities() {
  const amenities = [
    {
    icon: <Wifi className="h-8 w-8" />,
    title: "Free Wi-Fi",
    description: "High-speed internet access throughout the premises"
    },
    {
    icon: <Shield className="h-8 w-8" />,
    title: "24/7 Security",
    description: "Round-the-clock security personnel and CCTV surveillance"
    },
    {
    icon: <Coffee className="h-8 w-8" />,
    title: "Common Areas",
    description: "Shared spaces for studying and socializing"
    },
    {
    icon: <Users className="h-8 w-8" />,
    title: "Community Events",
    description: "Regular social activities and networking opportunities"
    },
    {
    icon: <MapPin className="h-8 w-8" />,
    title: "Prime Location",
    description: "Conveniently located near campus and amenities"
    },
    {
    icon: <Clock className="h-8 w-8" />,
    title: "Flexible Booking",
    description: "Easy booking process with flexible terms"
    }
    ];

  return (
    <section className="py-24 bg-gradient-to-b from-gray-50 to-white">
      <motion.div
        initial={{ opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.8 }}
        viewport={{ once: true }}
        className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
      >
        <div className="text-center mb-16">
          <h2 className="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
            Premium Amenities
          </h2>
          <div className="w-24 h-1 bg-blue-600 mx-auto mb-6"></div>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Everything you need for a comfortable and productive stay
          </p>
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {amenities.map((amenity, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              viewport={{ once: true }}
              className="group bg-white p-8 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100"
            >
              <div className="text-blue-600 mb-6 transform group-hover:scale-110 transition-transform duration-300">
                {amenity.icon}
              </div>
              <h3 className="text-2xl font-semibold text-gray-900 mb-4">
                {amenity.title}
              </h3>
              <p className="text-gray-600 leading-relaxed">
                {amenity.description}
              </p>
            </motion.div>
          ))}
        </div>
      </motion.div>
    </section>
  );
}

export { Amenities };