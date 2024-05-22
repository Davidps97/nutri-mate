import axios from "axios";

const app_id = "367af0ac";
const app_key = "3fdff35c2ae782d1be34fbfb37fe4176";

export const getFoods = async (ingredient) => {
  try {
    const response = await axios.get(
      "https://api.edamam.com/api/nutrition-data",
      {
        params: {
          app_id: app_id,
          app_key: app_key,
          "nutrition-type": "cooking",
          ingr: ingredient,
        },
        headers: {
          accept: "application/json",
        },
      }
    );
    return response.data;
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};
