import { getFoods } from "@/services/FoodServices";

const Home = ({ foodData }) => {
  return (
    <div>
      <div>aaaaa</div>
      {
        foodData &&
        <div>
          <h2>Información nutricional:</h2>
          <p>Nombre: {foodData.ingredients[0].text}</p>
          <p>Calorías: {foodData.calories}</p>
          <p>Peso total: {foodData.totalWeight}</p>
        </div>
      }
    </div>
  );
};

export const getServerSideProps = async () => {
  const foodData = await getFoods("rice 100gr");
  return {
    props: {
      foodData,
    },
  };
};

export default Home;