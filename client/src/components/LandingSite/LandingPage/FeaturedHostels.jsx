import { motion } from 'framer-motion';
import hostelA from '../../../assets/hostel-a.jpeg';
import hostelB from '../../../assets/hostel-b.jpeg';
import hostelC from '../../../assets/hostel-c.jpeg';

function FeaturedHostels() {
  const hostels = [
    {
    name: "Hostel A",
    image: hostelA,
    description: "A great place to stay with all amenities.",
    },
    {
    name: "Hostel B",
    image: hostelB,
    description: "Comfortable and affordable hostel for students.",
    },
    {
    name: "Hostel C",
    image: hostelC,
    description: "Modern facilities and a friendly environment.",
    },
    ];

  return (
    <section className="py-24 bg-gray-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-16">
          <h2 className="text-4xl lg:text-5xl font-bold mb-6">
            Featured Accommodations
          </h2>
          <div className="w-24 h-1 bg-blue-600 mx-auto mb-6"></div>
          <p className="text-xl text-gray-300 max-w-2xl mx-auto">
            Discover our selection of premium hostels designed for your comfort
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {hostels.map((hostel, index) => (
            <motion.div
              key={index}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: index * 0.1 }}
              viewport={{ once: true }}
              className="group relative overflow-hidden rounded-2xl"
            >
              <div className="aspect-w-16 aspect-h-9">
                <img
                  src={hostel.image}
                  alt={hostel.name}
                  className="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                />
              </div>
              <div className="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6">
                <h3 className="text-2xl font-bold mb-2">{hostel.name}</h3>
                <p className="text-gray-300">{hostel.description}</p>
                <button className="mt-4 px-6 py-2 bg-blue-600 rounded-full text-sm font-medium hover:bg-blue-700 transition-colors">
                  Learn More
                </button>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}

export { FeaturedHostels };