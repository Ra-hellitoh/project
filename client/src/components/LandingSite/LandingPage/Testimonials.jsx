import React from 'react';
import students from '../../../assets/students.jpg';

const testimonials = [
  {
    name: "John Doe",
    message: "Staying at Hostel A was a great experience! The facilities are top-notch.",
  },
  {
    name: "Jane Smith",
    message: "Hostel B offers a friendly environment and is very affordable.",
  },
  {
    name: "Emily Johnson",
    message: "I love the community here. Hostel C has everything a student needs!",
  },
];

function Testimonials() {
  return (
    <section className="relative py-20 text-white">
      <div className="flex flex-col lg:flex-row">
        <div className="w-full lg:w-2/3 p-10 text-center">
          <h2 className="text-4xl font-bold mb-10">What Our Students Say</h2>
          <div className="flex flex-wrap justify-center">
            {testimonials.map((testimonial, index) => (
              <div key={index} className="w-full sm:w-1/3 p-4">
                <div className="bg-white rounded-lg shadow-lg p-6">
                  <p className="italic text-gray-600 mb-4">"{testimonial.message}"</p>
                  <h3 className="font-bold">{testimonial.name}</h3>
                </div>
              </div>
            ))}
          </div>
        </div>
        <div className="w-full lg:w-1/3">
          <img src={students} alt="Students" className="object-cover h-full" />
        </div>
      </div>
    </section>
  );
}

export { Testimonials };