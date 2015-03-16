using System;

namespace CSharpClient
{
    /// <summary>
    /// Room Object
    /// </summary>
    public class Room
    {
        public int Id { get; set; }
        public string location_name { get; set; }
        public string manager_name { get; set; }
        public string Name { get; set; }
        public int Floor { get; set; }
        public string Description { get; set; }
    }
}
