import mysql.connector
from wordcloud import WordCloud
import matplotlib.pyplot as plt

try:
    # Koneksi ke database
    db_connection = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",  # Ganti dengan password yang sesuai
        database="analisis"
    )

    # Buat cursor untuk eksekusi query
    cursor = db_connection.cursor()

    # Query untuk mengambil data
    query = "SELECT text_bersih FROM dataset"
    cursor.execute(query)

    # Ambil semua data
    data = cursor.fetchall()

    # Gabungkan semua teks menjadi satu string
    text = ' '.join([str(row[0]) for row in data])

    # Buat WordCloud
    wordcloud = WordCloud(width=800, height=400, background_color='white').generate(text)

    # Simpan WordCloud sebagai gambar
    wordcloud.to_file('wordcloud_image.png')

except mysql.connector.Error as err:
    print("Error:", err)

finally:
    # Tutup koneksi
    if 'db_connection' in locals() and db_connection.is_connected():
        cursor.close()
        db_connection.close()
