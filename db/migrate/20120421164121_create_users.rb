class CreateUsers < ActiveRecord::Migration
  def change
    create_table :users do |t|
      t.integer :id
      t.string :username
      t.integer :bids

      t.timestamps
    end
    add_index :users, :id, :unique => true
  end
end
