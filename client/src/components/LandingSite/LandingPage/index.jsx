import { HeroSection } from "./HeroSection";
import { FeaturedHostels } from "./FeaturedHostels";
import { Amenities } from "./Amenities";
import { Testimonials } from "./Testimonials";
import { Footer } from "./Footer";

export default function Index() {
  return (
    <>
      <HeroSection />
      <FeaturedHostels />
      <Amenities />
      <Testimonials />
      <Footer />
    </>
  );
}