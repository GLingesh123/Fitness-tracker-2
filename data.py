import pandas as pd
import numpy as np

# Set seed for reproducibility
np.random.seed(42)

# Number of rows to generate
n_rows = 1000

# Initialize lists to store our data
current_weights = []
target_weights = []
daily_calories = []

# We assume a weight loss scenario:
# current weight is between 60 and 150 kg.
# target weight is set to be lower than current weight using a random factor.
# Calculate daily calories to burn assuming a 30-day weight loss goal.
for _ in range(n_rows):
    current = np.round(np.random.uniform(60, 150), 1)  # Current weight with one decimal place
    # Generate a reduction factor between 0.85 and 0.98: target weight is between 85% to 98% of current weight.
    reduction_factor = np.random.uniform(0.85, 0.98)
    target = np.round(current * reduction_factor, 1)
    weight_loss = current - target  # in kg
    # Calories that need to be burned per day over 30 days
    calories_per_day = np.round((weight_loss * 7700) / 30, 2)
    
    # Append to lists
    current_weights.append(current)
    target_weights.append(target)
    daily_calories.append(calories_per_day)

# Create a DataFrame
df = pd.DataFrame({
    'current_weight_kg': current_weights,
    'target_weight_kg': target_weights,
    'daily_calories_to_burn': daily_calories
})

# Display the first few rows
print(df.head())

# Save the dataset to a CSV file
df.to_csv("weight_loss_calorie_dataset.csv", index=False)
print("CSV file 'weight_loss_calorie_dataset.csv' with 1000 rows has been generated.")