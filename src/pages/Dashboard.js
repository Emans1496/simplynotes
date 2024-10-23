import React, { useEffect, useState } from 'react';
import axios from 'axios';

const Dashboard = () => {
  const [notes, setNotes] = useState([]);

  useEffect(() => {
    const fetchNotes = async () => {
      try {
        const response = await axios.get(`${process.env.REACT_APP_API_BASE_URL}/notes.php`, {
          headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
        });
        setNotes(response.data);
      } catch (error) {
        console.error('Error fetching notes', error);
      }
    };

    fetchNotes();
  }, []);

  return (
    <div className="container">
      <h2>Your Notes</h2>
      <ul>
        {notes.map((note) => (
          <li key={note.id}>{note.content}</li>
        ))}
      </ul>
    </div>
  );
};

export default Dashboard;