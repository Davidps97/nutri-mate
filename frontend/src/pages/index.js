import { useState, useEffect } from "react";
import { getFoods } from "@/services/FoodServices";

export default function Home() {
  const [foodData, setFoodData] = useState(null);
  useEffect(() => {
    const fetchData = async () => {
      const data = await getFoods("rice 100gr");
      setFoodData(data);
    };
    fetchData();
  }, []);

  return (
    <div>
      <div>aaaaa</div>
      {foodData && (
        <div>
          <h2>Información nutricional:</h2>
          <p>Nombre: {foodData.ingredients[0].text}</p>
          <p>Calorías: {foodData.calories}</p>
          <p>Peso total: {foodData.totalWeight}</p>
        </div>
      )}
    </div>
  );
}
